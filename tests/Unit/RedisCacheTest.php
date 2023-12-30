<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sajadsdi\LaravelSettingPro\Cache\Drivers\Redis;

class RedisCacheTest extends TestCase
{
    private Redis $redisCache;

    protected function setUp(): void
    {
        $config = [
            'connection' => [
                'scheme'   => env('REDIS_SCHEME', 'tcp'),
                'host'     => env('REDIS_HOST', '127.0.0.1'),
                'username' => env('REDIS_USERNAME'),
                'password' => env('REDIS_PASSWORD'),
                'port'     => env('REDIS_PORT', '6379'),
                'database' => env('REDIS_CACHE_DB', '1'),
            ],
            'options'    => [
                'cluster' => env('REDIS_CLUSTER', 'redis'),
                'prefix'  => 'setting_test_caches_',
            ],
        ];

        // Mocking the Redis class
        $this->redisCache = new Redis($config);
    }


    public function testSetAndGet()
    {
        $key  = 'test_key';
        $data = 'test_data';

        // Test 'set' operation
        $this->redisCache->set($key, $data);

        // Test 'get' operation
        $result = $this->redisCache->get($key);

        $this->assertEquals($data, $result);
    }


    public function testClear()
    {
        $key  = 'test_key_1';
        $data = 'test_data_1';

        $this->redisCache->set($key, $data);

        $this->redisCache->clear($key);

        $result = $this->redisCache->get($key);

        $this->assertNull($result);
    }

    public function testClearAll()
    {
        $key1  = 'test_key_1';
        $key2  = 'test_key_2';
        $data1 = 'test_data_1';
        $data2 = 'test_data_2';

        // Set values using 'set' operation
        $this->redisCache->set($key1, $data1);
        $this->redisCache->set($key2, $data2);

        // Clear all values using 'clearAll' operation
        $this->redisCache->clearAll();

        // Attempt to get the values after clearing all
        $result1 = $this->redisCache->get($key1);
        $result2 = $this->redisCache->get($key2);

        // Assert that the results should be null after clearing all
        $this->assertNull($result1);
        $this->assertNull($result2);
    }
}
