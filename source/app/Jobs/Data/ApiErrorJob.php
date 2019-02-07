<?php

namespace App\Jobs\Data;

use App\Cache\RedisCache;
use App\Jobs\Job;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob;

class ApiErrorJob extends Job
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var \Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob
     */
    protected $job;

    /**
     * @var RedisCache
     */
    protected $cache;

    /**
     * Array of job parameters.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * StoreDataJob constructor.
     *
     * @param \Laravel\Lumen\Application $app - Application container.
     *
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Storage action.
     *
     * @param \Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob $job - Kafka Job object.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function fire(KafkaJob $job)
    {
        try {
            $this->job = $job;
            $payload = $job->payload();
            $payloadData = $payload['data'];
            $data = $payloadData['data'];
            $this->cache = new RedisCache($payloadData['type'], $payloadData['id']);
            // Since it's an error it should expire in shorter time than regular cache.
            $this->cache->setTtl(60);
            $this->cache->set($payload['data']);
            Log::error(sprintf('%i: %s', $data['code'], $data['message']), $payloadData);
            $this->delete();
        } catch (\Exception $e) {
            $this->delete();
            throw $e;
        }
    }

    /**
     * Delete and item from the queue.
     *
     * @return void
     */
    public function delete()
    {
        $this->job->delete();
    }

}
