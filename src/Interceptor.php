<?php

declare(strict_types=1);

namespace Ostrolucky\NewrelicDatastoreRedisClusterExtension;

use RedisCluster;

use function function_exists;

class Interceptor
{
    /**
     * @var callable
     */
    private $wrappedInterceptor;

    public function __construct(callable $wrappedInterceptor)
    {
        $this->wrappedInterceptor = $wrappedInterceptor;
    }

    /**
     * @param mixed[] $args
     *
     * @return mixed
     */
    public function __invoke(object $instance, string $method, array $args, ?string $connection)
    {
        if (!function_exists('newrelic_record_datastore_segment') || !$instance instanceof RedisCluster) {
            // Nothing to do here. NR PHP extension supports \Redis and Predis. Only thing it doesn't support is \RedisCluster
            return ($this->wrappedInterceptor)($instance, $method, $args, $connection);
        }

        return newrelic_record_datastore_segment(
            fn () => ($this->wrappedInterceptor)($instance, $method, $args, $connection),
            ['product' => 'Redis', 'operation' => $method],
        );
    }
}
