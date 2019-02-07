<?php

namespace App\Http\Controllers;

use App\Cache\RedisCache;
use Exception;
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
//        return response()->json(array_merge($jobData, ['pushRawCorrelationId' => $pushRawCorrelationId]));
        return $this->get($request, $pushRawCorrelationId);
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
        return $this->get($request, $id);
//        return response()->json(['jobName' => $jobName, 'jobData' => $jobData, 'queue' => 'store']);
    }

    public function get(Request $request, $id)
    {
        try {
            $data = $this->getData($request, $id);
            Log::debug(print_r($data, true), [__METHOD__, 103]);
            if ($data->error) {
                Log::debug('here', [__METHOD__, 106]);
                return $this->errorResponse($data);
            }
            $modelAbstract = "model.{$data->type}";
            $model = $this->app->makeWith($modelAbstract, (array) $data);
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

    /**
     * @param Request $request
     * @param $id
     * @param bool $getEvent
     *
     * @return mixed
     */
    protected function getData(Request$request, $id)
    {
        static $i = 0;
        $data = $this->getCachedData($this->getType(), $id);
        if (empty($data)) {
            $i++;
            if ($i == 1) {
                $this->getEvent($request, $id);
            }
            return $this->getData($request, $id);
        } else {
            Log::debug(print_r($data, true), [__METHOD__, 136]);
            return (object) $data;
        }
    }

    protected function getEvent(Request $request, $id)
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

    /**
     * @param object $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($data)
    {
        $filter = [
            'id',
            'type',
            'error',
            'message',
            'code',
        ];
        $filtered = array_filter(
            (array) $data,
            function ($key) use ($filter) {
                return in_array($key, $filter);
            },
            ARRAY_FILTER_USE_KEY
        );
        return response()->json($filtered, $filtered['code']);
    }
}
