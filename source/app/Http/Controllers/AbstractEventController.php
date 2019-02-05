<?php

namespace App\Http\Controllers;

use App\Cache\RedisCache;
use Illuminate\Http\Request;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Connectors\KafkaConnector;

abstract class AbstractEventController extends Controller
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
     * @var RedisCache
     */
    protected $cache;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->connector = new KafkaConnector($this->app);
        $this->queue = $this->connector->connect($this->getConfig());
        $this->correlationId = uniqid('', true);
        $this->queue->setCorrelationId($this->correlationId);
    }


    protected function getCachedData($type, $id = null)
    {
        $this->cache = new RedisCache($type, $id);
        return $this->cache->get();
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
