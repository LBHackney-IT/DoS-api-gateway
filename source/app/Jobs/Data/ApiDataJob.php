<?php

namespace App\Jobs\Data;

use App\Jobs\Job;
use Laravel\Lumen\Application;
use Rapide\LaravelQueueKafka\Queue\Jobs\KafkaJob;

class ApiDataJob extends Job
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
            $payload = $this->job->payload();
            /** @var \App\Jobs\Data\ApiDataSave $dataSave */
            $dataSave = $this->app->makeWith('api.data.save', $payload);
            $dataSave->dispatch();
            $this->delete();
            return;
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
