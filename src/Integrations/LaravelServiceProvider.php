<?php

namespace Dusterio\PlainSqs\Integrations;

use Dusterio\PlainSqs\Sqs\Connector;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
//use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Contracts\Queue\Job;

/**
 * Class CustomQueueServiceProvider
 * @package App\Providers
 */
class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/sqs-plain.php' => config_path('sqs-plain.php')
        ]);

        // The callback below is formatted for Laravel 5.2 and will generate an error as we are running 5.1
        /*Queue::after(function (JobProcessed $event) {
            $event->job->delete();
        });*/

        // This is the proper implementation for Laravel 5.1
        Queue::after(function ($connectionName, Job $job) {
            $job->delete();
        });
    }

    /**
     * @return void
     */
    public function register()
    {
         $this->app->booted(function () {
            $this->app['queue']->extend('sqs-plain', function () {
                return new Connector();
            });
        });
    }
}
