<?php

namespace Usmonaliyev\LaravelMetrics;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
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

        $this->publishes([__DIR__ . '/../config/metric.php' => config_path('metric.php')], 'config');

        Config::set('database.redis.metric', config('metric.redis.metric'));
    }
}
