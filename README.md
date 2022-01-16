<h1 align="center">ostrolucky/newrelic-datastore-redis-cluster-extension</h1>

<p align="center">
    <strong>Provides missing \RedisCluster datastore integration to NewRelic PHP extension</strong>
</p>

## About

You might have found out NewRelic PHP extension doesn't support to monitor \RedisCluster queries.

You can see that in [their knowledge base](https://newrelic.zendesk.com/hc/en-us/articles/360059017673-PHP-Redis-issue)
as well as in [their bug tracker](https://ghttps://docs.newrelic.com/docs/apm/agents/php-agent/php-agent-api/newrelic_record_datastore_segment/ithub.com/newrelic/newrelic-php-agent/issues/130).

What NewRelic support recommends doing,
is to use custom instrumentation via [newrelic_record_datastore_segment](https://docs.newrelic.com/docs/apm/agents/php-agent/php-agent-api/newrelic_record_datastore_segment/). However, doing that manually is cumbersome. Redis has lot of functions.. That's where this package comes in play. It's meant to be used along [snc/redis-bundle](https://github.com/snc/SncRedisBundle).
You install it and voila, you should be able to see redis commands in your NewRelic APM screen.

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require ostrolucky/newrelic-datastore-redis-cluster-extension
```

There is also one prerequisite for snc_redis: Logging of your snc_redis client MUST be enabled.


## Copyright and License

The ostrolucky/newrelic-datastore-redis-cluster-extension library is copyright © [Gabriel Ostrolucký](mailto:gabriel.ostrolucky@gmail.com)
and licensed for use under the terms of the
MIT License (MIT). Please see [LICENSE](LICENSE) for more information.


