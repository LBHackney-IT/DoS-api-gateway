<?php

namespace App\Cache;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class RedisCache
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $json;

    /**
     * @var string
     */
    private $cacheId;

    /**
     * Redis cache TTL.
     *
     * @var int
     */
    private $ttl = 3600;

    /**
     * CacheStore constructor.
     *
     * @param string $type
     * @param string|null $id
     * @param array|string|null $json
     */
    public function __construct($type, $id = null)
    {
        $this->type = $type;
        $this->id = $id;
        $this->setCacheId();
    }

    /**
     * @param array|string $json
     */
    public function set($json)
    {
        $json = json_encode($json);
        Redis::set($this->getCacheId(), $json);
        Redis::expire($this->getCacheId(), $this->getTtl());
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $json = Redis::get($this->getCacheId());
        return json_decode($json);
    }

    /**
     * @return string
     */
    public function getCacheId(): string
    {
        return $this->cacheId;
    }

    private function setCacheId()
    {
        $items = [
            $this->type,
            $this->id,
        ];
        $this->cacheId = implode(':', $items);
    }

    /**
     * Get the current TTL for the Redis cache, in sec.
     *
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * Set the current TTL for the Redis cache, in sec.
     *
     * @param int $ttl
     */
    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }
}
