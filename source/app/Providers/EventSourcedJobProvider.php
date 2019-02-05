<?php

namespace app\Providers;

use App\Jobs\Data\ApiDataJob;
use App\Jobs\Data\ApiDataSave;
use function foo\func;
use Illuminate\Support\ServiceProvider;

class EventSourcedJobProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerDependencies();
    }

    protected function registerDependencies()
    {
        $this->app->bind('job.api.data', function ($app) {
            return new ApiDataJob($app);
        });

        $this->app->bind('api.data.save', function ($app, $data) {
            return new ApiDataSave($app, $data);
        });
    }

    public function provides()
    {
        return [
            'job.api.data',
            'api.data.save',
        ];
    }
}
