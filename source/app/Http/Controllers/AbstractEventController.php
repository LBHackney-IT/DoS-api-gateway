<?php

namespace App\Http\Controllers;

use App\Cache\RedisCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector;

abstract class AbstractEventController extends Controller implements EventControllerInterface
{
    /**
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /** @var \Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector */
    protected $connector;

    /**
     * @var \Rapide\LaravelQueueKafka\Queue\KafkaQueue
     */
    protected $queue;

    /**
     * @var string
     */
    protected $correlationId;

    /**
     * Content type.
     *
     * @var string
     */
    public $type;

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * AbstractEventController constructor.
     *
     * @throws EventControllerConfigurationException
     */
    public function __construct()
    {
        if (!$this->type) {
            throw new EventControllerConfigurationException('Event controller type not set');
        }
        $this->app = app();
        $this->connector = new KafkaConnector($this->app);
        $this->queue = $this->connector->connect($this->getConfig());
        $this->correlationId = uniqid('', true);
        $this->queue->setCorrelationId($this->correlationId);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function create(Request $request)
    {
//        return response()->json($this->app->router->namedRoutes);

        $jobName = 'job.data.store';
        $jobData = [
            'type' => $this->getType(),
            'values' => $request->all(),
        ];
        $pushRawCorrelationId = $this->queue->push($jobName, $jobData, 'store');
//        echo $pushRawCorrelationId;
//        sleep(2);
//        return $this->get($request, $pushRawCorrelationId);
        return response()->json(array_merge($jobData, ['pushRawCorrelationId' => $pushRawCorrelationId]));
    }

    public function update(Request $request, $id)
    {
        $jobName = 'job.data.update';
        $jobData = [
            'type' => $this->getType(),
            'id' => $id,
            'values' => $request->all(),
        ];
        $this->queue->setCorrelationId($id);
        $this->queue->push($jobName, $jobData, 'store');
        sleep(2);
        return $this->get($request, $id);
//        return response()->json(['jobName' => $jobName, 'jobData' => $jobData, 'queue' => 'store']);
    }

    public function get(Request $request, $id)
    {
        try {
            $data = $this->getCachedData($this->getType(), $id);
//            Log::debug(print_r($data, true), [__METHOD__]);
            if (empty($data)) {
                $this->getEvent($request, $id);
                sleep(2);
                redirect()->route('getProvider', ['id' => $id]);
//                return $this->getCacheSubscribe('provider', $id);
            }
            $model = new Provider($data);
////            Log::debug(print_r($model->responseData(), true), [__METHOD__]);
            return response()->json($model);
        } catch (Exception $e) {
            $data = [
                'error' => true,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
            return response()->json($data, 500);
        }
    }

    public function getEvent(Request $request, $id)
    {
        $jobName = 'job.data.get';
        $jobData = [
            'queryType' => 'select',
            'type' => $this->getType(),
            'id' => $id,
            'values' => $request->all(),
        ];
        $this->queue->setCorrelationId($id);
        $this->queue->push($jobName, $jobData, 'store');
    }

    public function getIndex()
    {
        $jobName = 'job.data.get';
        $jobData = [
            'queryType' => 'index',
            'type' => $this->getType(),
        ];
        $this->queue->push($jobName, $jobData, 'store');
    }

    public function delete(Request $request, $id)
    {
//        $jobName = 'job.data.delete';
//        $jobData = [
//            'queryType' => 'delete',
//            'type' => $this->getType(),
//            'id' => $id,
//        ];
//        $this->queue->setCorrelationId($id);
//        $this->queue->push($jobName, $jobData, 'store');
//        return response()->json(['jobName' => $jobName, 'jobData' => $jobData, 'queue' => 'store']);

        $code = 403;
        $response = [
            'error' => true,
            'code' => $code,
            'message' => 'DELETE method not yet implemented',
        ];
        return response()->json($response, $code);
    }

    protected function getCachedData($type, $id = null)
    {
        $this->cache = new RedisCache($type, $id);
        return $this->cache->get();
    }

    protected function getCacheSubscribe($type, $id = null)
    {
        $this->cache = new RedisCache($type, $id);
        $this->cache->getSubscribe();
    }

    /**
     * Get the queue config.
     *
     * @return array
     */
    protected function getConfig()
    {
        return [
            'queue' => config('queue.connections.kafka.queue'),
            'brokers' => config('queue.connections.kafka.brokers'),
            'consumer_group_id' => config('queue.connections.kafka.consumer_group_id'),
        ];
    }
}
