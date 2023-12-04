<?php

namespace Usmonaliyev\LaravelMetrics\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\Redis;

class QueryListener
{
    private PredisConnection $redis;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->redis = Redis::connection(config('metric.connection'));
    }

    /**
     * Handle the event.
     */
    public function handle(QueryExecuted $query): void
    {
        if (config('metric.control.query_counter')) {

            $this->registerCount($query);
        }
        if (config('metric.control.query_speed')) {

            $this->registerSpeed($query);
        }
    }

    /**
     * To register count of queries
     *
     * @param QueryExecuted $query
     * @return void
     */
    private function registerCount(QueryExecuted $query): void
    {
        $this->redis->zincrby(config('metric.prefix.query.count'), 1, $query->sql);
    }

    /**
     * To register speed of queries
     *
     * @param QueryExecuted $query
     * @return void
     */
    private function registerSpeed(QueryExecuted $query): void
    {
        $previousSpeed = $this->redis->zscore(config('metric.prefix.query.speed'), $query->sql);

        if (is_null($previousSpeed) || $query->time > $previousSpeed) {

            $this->redis->zadd(config('metric.prefix.query.speed'), $query->time, $query->sql);
        }
    }
}
