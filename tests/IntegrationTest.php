<?php

declare(strict_types=1);

namespace Ostrolucky\Test\NewrelicDatastoreRedisClusterExtension;

use Ostrolucky\NewrelicDatastoreRedisClusterExtension\NewrelicDatastoreRedisClusterExtensionBundle;
use PHPUnit\Framework\TestCase;
use Snc\RedisBundle\SncRedisBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;

use function md5;
use function mt_rand;
use function sys_get_temp_dir;

class IntegrationTest extends TestCase
{
    public function testIntegrationWorks(): void
    {
        $kernel = new class ('test', false) extends Kernel {
            /** @return iterable<BundleInterface> */
            public function registerBundles(): iterable
            {
                return [
                    new FrameworkBundle(),
                    new SncRedisBundle(),
                    new NewrelicDatastoreRedisClusterExtensionBundle(),
                ];
            }

            public function registerContainerConfiguration(LoaderInterface $loader): void
            {
                $loader->load(static function (ContainerBuilder $container): void {
                    $container->loadFromExtension(
                        'snc_redis',
                        [
                            'clients' => [
                                'default' => [
                                    'type' => 'phpredis',
                                    'alias' => 'default',
                                    'dsn' => 'redis://localhost',
                                    'logging' => true,
                                ],
                            ],
                        ],
                    );

                    $container->setAlias('test.redis', new Alias('snc_redis.default', true));
                });
            }

            public function getProjectDir(): string
            {
                static $projectDir = null;

                return $projectDir ??= sys_get_temp_dir() . '/sf_kernel_' . md5((string) mt_rand());
            }
        };

        $kernel->boot();
        $this->assertEquals('foo', $kernel->getContainer()->get('test.redis')->echo('foo'));
    }
}
