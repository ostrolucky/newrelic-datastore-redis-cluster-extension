<?php

declare(strict_types=1);

namespace Ostrolucky\NewrelicDatastoreRedisClusterExtension;

use RedisCluster;
use Snc\RedisBundle\Logger\RedisCallInterceptor;

use function function_exists;

class Interceptor
{
    private RedisCallInterceptor $wrappedInterceptor;

    public function __construct(RedisCallInterceptor $wrappedInterceptor)
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
            return $this->wrappedInterceptor->__invoke($instance, $method, $args, $connection);
        }

        return newrelic_record_datastore_segment(
            fn () => $this->wrappedInterceptor->__invoke($instance, $method, $args, $connection),
            ['product' => 'Redis', 'operation' => $method],
        );
    }
}
