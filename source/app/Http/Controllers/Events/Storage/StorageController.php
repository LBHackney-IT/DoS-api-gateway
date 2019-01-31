<?php

namespace app\Http\Controllers\Events\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector;

/**
 * Controller for storing stuff in the data store microservice
 *
 * @package app\Http\Controllers\Storage
 */
class StorageController extends Controller
{
    /**
     * @var \Rapide\LaravelQueueKafka\Queue\KafkaQueue
     */
    protected $queue;

    /**
     * Test storage.
     *
     * @param \Illuminate\Http\Request $request - An HTTP request object.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request)
    {
        // $build = [true];
        $app = app();
        $connector = new KafkaConnector($app);
        $this->queue = $connector->connect($this->getConfig());
        $jobName = 'job.data.get';
        $jobData = $request->all();
        $this->queue->push($jobName, $jobData, 'store');
        return response()->json($jobData);
    }

    public function provider(Request $request)
    {
        $app = app();
        $connector = new KafkaConnector($app);
        $this->queue = $connector->connect($this->getConfig());
        $jobName = 'job.data.store';
        $jobData = [
            'type' => 'providers',
            'values' => $request->all(),
        ];
        $this->queue->push($jobName, $jobData, 'store');
        return response()->json($jobData);
    }

    public function providerUpdate(Request $request, $id)
    {
        $app = app();
        $connector = new KafkaConnector($app);
        $this->queue = $connector->connect($this->getConfig());
        $jobName = 'job.data.update';
        $jobData = [
            'type' => 'providers',
            'id' => $id,
            'values' => $request->all(),
        ];
        $this->queue->push($jobName, $jobData, 'store');
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
