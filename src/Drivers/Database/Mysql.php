<?php

namespace Sajadsdi\LaravelSettingPro\Drivers\Database;

use PDO;
use PDOException;
use Sajadsdi\LaravelSettingPro\Contracts\DatabaseDriverInterface;
use Sajadsdi\LaravelSettingPro\Exceptions\DatabaseConnectionException;

class Mysql implements DatabaseDriverInterface
{
    private PDO    $connection;
    private string $table = 'settings';
    private string $prefix;

    /**
     * Retrieves the value of a setting based on the provided key.
     *
     * @param string $key The key of the setting.
     * @return mixed|null The value of the setting, or null if not found.
     */
    public function getSetting(string $key): mixed
    {
        $setting = $this->get($key);

        return $setting ? json_decode($setting['data'], true) : null;
    }

    /**
     * Sets the value of a setting based on the provided key.
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
     * Delete a setting row with key.
     * @param string $key
     * @return void
     */
    public function deleteSetting(string $key): void
    {
        $query = $this->connection->prepare("DELETE FROM {$this->table()} WHERE setting = ?");

        $query->execute([$key]);
    }

    /**
     * @throws DatabaseConnectionException
     */
    public function __construct(array $config)
    {
        $this->prefix = $config['prefix'];
        $this->setConnection($config);
    }

    /**
     * This method return a full name of setting table.
     * @return string
     */
    private function table(): string
    {
        return $this->prefix ? $this->prefix . '_' . $this->table : $this->table;
    }

    /**
     * Create a PDO connection.
     * @param $config
     * @return void
     * @throws DatabaseConnectionException
     */
    private function setConnection($config): void
    {
        try {
            $dsn = $config['driver'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['database'] . ";charset=".$config['charset'];

            $this->connection = new PDO($dsn, $config['username'], $config['password']);

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            throw new DatabaseConnectionException('MySQL', $e->getMessage());
        }
    }

    /**
     * Get query to retrieve a setting row with key.
     * @param string $key
     * @return mixed
     */
    private function get(string $key): mixed
    {
        $query = $this->connection->prepare("SELECT * FROM {$this->table()} WHERE setting = ?");
        $query->execute([$key]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update query for set a setting with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    private function set(string $key, mixed $data): void
    {
        $query = $this->connection->prepare("UPDATE {$this->table()} SET updated_at = ? , data = ? WHERE setting = ?");

        $query->execute([date('Y-m-d H:i:s'), json_encode($data, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $key]);
    }

    /**
     * Insert a row in setting table with key and data.
     * @param string $key
     * @param mixed $data
     * @return void
     */
    private function insert(string $key, mixed $data): void
    {
        $query = $this->connection->prepare("INSERT INTO {$this->table()} (setting,data,created_at,updated_at) VALUES (?,?,?,?)");

        $date  = date('Y-m-d H:i:s');

        $query->execute([$key, json_encode($data, JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $date, $date]);
    }
}
