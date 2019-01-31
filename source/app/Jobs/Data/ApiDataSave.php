<?php

namespace App\Jobs\Data;

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

    public function __construct($app, $payload)
    {
        $this->app = $app;
        $this->payload = $payload;
    }

    public function dispatch()
    {
        // Do something
        Log::debug(print_r($this->payload, true));
    }
}
