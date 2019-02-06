<?php

namespace App\Jobs\Data;

use App\Cache\RedisCache;
use App\Component\Model\Provider;
use Illuminate\Support\Facades\Log;

class ApiDataSave
{
    /**
     * Laravel application container.
     *
     * @var \Laravel\Lumen\Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var \App\Component\Model\ModelInterface
     */
    protected $model;

    /**
     * @var RedisCache
     */
    protected $cache;

    public function __construct($app, $payload)
    {
        $this->app = $app;
        $this->payload = $payload;
    }

    /**
     * @throws \Exception
     */
    public function dispatch()
    {
        if (empty($this->getPayloadData())) {
            throw new \Exception('No payload data');
        }
        $this->setModel();
        $this->cacheSetPayloadData();
//        Log::debug(print_r($this->cacheGetPayloadData(), true), [__METHOD__]);
    }

    /**
     *
     * @return void
     *
     * @throws \Exception
     */
    protected function setModel()
    {
        $data = $this->getPayloadData();
        if (!empty($data['type'])) {
            $model = false;
            switch ($data['type']) {
                case 'provider':
                    $data = (object) $data;
                    $model = new Provider($data);
                    break;
            }
            if ($model) {
                $this->model = $model;
            }
        } else {
            throw new \Exception('No data type');
        }
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getPayloadData(): array
    {
        return empty($this->getPayload()['data']) ? [] : $this->getPayload()['data'];
    }

    /**
     *
     * @return void
     *
     * @throws \Exception
     */
    public function cacheSetPayloadData()
    {
        $this->cache = new RedisCache($this->model->type(), $this->model->id());
        $this->cache->set($this->getPayloadData());
    }

    public function cacheGetPayloadData()
    {
        return $this->cache->get();
    }
}
