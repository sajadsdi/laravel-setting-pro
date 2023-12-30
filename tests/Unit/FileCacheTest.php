<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sajadsdi\LaravelSettingPro\Cache\Drivers\File;

class FileCacheTest extends TestCase
{
    private File $fileCache;
    private string $testPath;

    protected function setUp(): void
    {
        // Create a temporary directory for testing
        $this->testPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'file_cache_test';

        if(!is_dir($this->testPath)) {
            mkdir($this->testPath, 0777, true);
        }

        $config = [
            'path' => $this->testPath,
        ];

        // Creating an instance of the File class
        $this->fileCache = new File($config);
    }

    protected function tearDown(): void
    {
        // Remove the temporary directory after testing
        $this->deleteDirectory($this->testPath);
    }


    public function testSetAndGet()
    {
        $key  = 'test_key';
        $data = 'test_data';

        // Test 'set' operation
        $this->fileCache->set($key, $data);

        // Test 'get' operation
        $result = $this->fileCache->get($key);

        $this->assertEquals($data, $result);
    }

    public function testClear()
    {
        $key  = 'test_key';
        $data = 'test_data';

        // Set a value using 'set' operation
        $this->fileCache->set($key, $data);

        // Clear the value using 'clear' operation
        $this->fileCache->clear($key);

        // Attempt to get the value after clearing
        $result = $this->fileCache->get($key);

        // Assert that the result should be null after clearing
        $this->assertNull($result);
    }

    public function testClearAll()
    {
        $key1  = 'test_key_1';
        $key2  = 'test_key_2';
        $data1 = 'test_data_1';
        $data2 = 'test_data_2';

        // Set values using 'set' operation
        $this->fileCache->set($key1, $data1);
        $this->fileCache->set($key2, $data2);

        // Clear all values using 'clearAll' operation
        $this->fileCache->clearAll();

        // Attempt to get the values after clearing all
        $result1 = $this->fileCache->get($key1);
        $result2 = $this->fileCache->get($key2);

        // Assert that the results should be null after clearing all
        $this->assertNull($result1);
        $this->assertNull($result2);
    }

    private function deleteDirectory($dir)
    {
        if (is_dir($dir)) {
            $this->fileCache->clearAll();

            rmdir($dir);
        }
    }
}
