<?php

namespace Usmonaliyev\LaravelMetrics;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Usmonaliyev\LaravelMetrics\Console\Commands\NotifyReportCommand;
use Usmonaliyev\LaravelMetrics\Listeners\QueryListener;

class LaravelMetricServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/metric.php', 'metric');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('metric.enable')) {

            Event::listen(QueryExecuted::class, QueryListener::class);
        }

        if ($this->app->runningInConsole()) {

            $this->publishes([__DIR__ . '/../config/metric.php' => config_path('metric.php')], 'config');

            $this->commands([
                NotifyReportCommand::class,
            ]);
        }

        Config::set('database.redis.metric', config('metric.redis.metric'));

        /**
         * Register schedule
         */
        $this->app->booted(function () {

            $this->app
                ->make(Schedule::class)
                ->command('metric:notify-report')
                ->cron(config('metric.notify.cron'));
        });
    }
}
