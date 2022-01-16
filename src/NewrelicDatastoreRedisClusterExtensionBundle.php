<?php

declare(strict_types=1);

namespace Ostrolucky\NewrelicDatastoreRedisClusterExtension;

use Snc\RedisBundle\Logger\RedisCallInterceptor;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NewrelicDatastoreRedisClusterExtensionBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new class extends Extension {
            /**
             * @param mixed[] $configs
             */
            public function load(array $configs, ContainerBuilder $container)
            {
                $container->setDefinition(
                    Interceptor::class,
                    (new Definition(Interceptor::class, [new Reference(Interceptor::class . '.nrinner')]))
                        ->setDecoratedService(RedisCallInterceptor::class, Interceptor::class . '.nrinner'),
                );
            }

            public function getAlias(): string
            {
                return self::class;
            }
        };
    }
}
