<?php

namespace Sajadsdi\LaravelSettingPro\Drivers\Database;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use MongoDB\Collection;
use Sajadsdi\LaravelSettingPro\Contracts\DatabaseDriverInterface;
use Sajadsdi\LaravelSettingPro\Exceptions\DatabaseConnectionException;

class MongoDB implements DatabaseDriverInterface
{
    private Collection $connection;
    private string     $collection = 'settings';

    /**
     * Retrieves the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @return mixed|null The value of the setting, or null if not found.
     */
    public function getSetting(string $key): mixed
    {
        $setting = $this->get($key);

        return $setting ? $setting['data'] : null;
    }

    /**
     * Update the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @param mixed $data The value to be set for the setting.
     * @return void
     */
    public function setSetting(string $key, mixed $data): void
    {
        $setting = $this->get($key);

        if ($setting) {
            $this->set($key, $data);
        } else {
            $this->insert($key, $data);
        }
    }

    /**
     * Delete a setting document with key.
     * @param string $key
     * @return void
     */
    public function deleteSetting(string $key): void
    {
        $this->connection->deleteOne(['setting' => $key]);
    }

    /**
     * @throws DatabaseConnectionException
     */
    public function __construct(array $config)
    {
        $this->setConnection($config);
    }

    /**
     * Create connection to MongoDB database.
     * @param $config
     * @return void
     * @throws DatabaseConnectionException
     */
    private function setConnection($config): void
    {
        try {
            $uri = "mongodb://{$config['host']}:{$config['port']}";

            if ($config['username'] && $config['password']) {
                $uri = "mongodb://{$config['username']}:{$config['password']}@{$config['host']}:{$config['port']}/{$config['database']}";
            }

            $client = new Client($uri, $config['options']);

            // The selectDatabase method is lazy and won't immediately cause a connection
            $database   = $client->selectDatabase($config['database']);
            $collection = $database->selectCollection($this->collection);

            // The following line triggers an actual connection and will throw a ConnectionException if it fails
            $database->listCollections();

            $this->connection = $collection;

        } catch (\Exception $e) {
            throw new DatabaseConnectionException('MongoDB', $e->getMessage());
        }
    }

    /**
     * get setting document with key.
     * @param string $key
     * @return array|object|null
     */
    private function get(string $key): array|null|object
    {
        return $this->connection->findOne(['setting', $key]);
    }

    /**
     * Update a setting document with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    private function set(string $key, mixed $data): void
    {
        $date = new UTCDateTime((new \DateTime())->getTimestamp() * 1000);

        $document = ['data' => $data, 'updated_at' => $date];

        $this->connection->updateOne(
            ['setting' => $key],
            ['$set' => $document],
            ['upsert' => true]
        );
    }

    /**
     * Insert a document with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    private function insert(string $key, mixed $data): void
    {
        $date = new UTCDateTime((new \DateTime())->getTimestamp() * 1000);

        $document = [
            'setting'    => $key,
            'data'       => $data,
            'created_at' => $date,
            'updated_at' => $date
        ];

        $this->connection->insertOne($document);
    }
}
