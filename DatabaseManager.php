<?php

namespace Illuminate\Database;

use PDO;
use PDOException;

class DatabaseManager
{
    protected $connections = [];
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function connection($name = null)
    {
        if (is_null($name)) {
            $name = $this->getDefaultConnection();
        }

        if (!isset($this->connections[$name])) {
            $this->connections[$name] = $this->makeConnection($this->app['config']['database.connections'][$name]);
        }

        return $this->connections[$name];
    }

    protected function makeConnection(array $config)
    {
        $driver = $config['driver'];

        if ($driver === 'mysql') {
            return new MySqlConnection($config);
        } elseif ($driver === 'pgsql') {
            return new PostgresConnection($config);
        } elseif ($driver === 'sqlite') {
            return new SQLiteConnection($config);
        }

        throw new InvalidArgumentException("Unsupported database driver [$driver].");
    }

    protected function getDefaultConnection()
    {
        return $this->app['config']['database.default'];
    }
}


class MySqlConnection extends Connection
{
    protected function getDsn(array $config)
    {
        extract($config, EXTR_SKIP);

        return "mysql:host={$host};dbname={$database};charset={$charset}";
    }
}



