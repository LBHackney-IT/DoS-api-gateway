<?php

namespace App\Providers;

use App\Component\Model\Contact;
use App\Component\Model\CostOption;
use App\Component\Model\Provider;
use App\Component\Model\Service;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerDependencies();
    }

    /**
     * Register adapter dependencies in the container.
     */
    protected function registerDependencies()
    {
        $this->app->bind('model.provider', function ($app, $data) {
            return new Provider((object) $data);
        });
        $this->app->bind('model.service', function ($app, $data) {
            return new Service((object) $data);
        });
        $this->app->bind('model.contact', function ($app, $data) {
            return new Contact((object) $data);
        });
        $this->app->bind('model.costoption', function ($app, $data) {
            return new CostOption((object) $data);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'model.provider',
            'model.service',
            'model.contact',
            'model.costoption',
        ];
    }
}
