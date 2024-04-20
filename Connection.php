<?php 


class Connection
{
    protected $pdo;

    public function __construct(array $config)
    {
        $dsn = $this->getDsn($config);
        $username = $config['username'] ?? null;
        $password = $config['password'] ?? null;
        $options = $config['options'] ?? [];

        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            throw new ConnectionException($e->getMessage());
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function table($table)
    {
        return new QueryBuilder($this, $table);
    }

    protected function getDsn(array $config)
    {
        // Implementation for generating DSN based on the driver and configuration
    }
}


class ConnectionException extends Exception
{
    
}

class PostgresConnection extends Connection
{
    protected function getDsn(array $config)
    {
        extract($config, EXTR_SKIP);

        return "pgsql:host={$host};port={$port};dbname={$database}";
    }
}

class SQLiteConnection extends Connection
{
    protected function getDsn(array $config)
    {
        return "sqlite:{$config['database']}";
    }
}