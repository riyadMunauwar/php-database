<?php 

class QueryBuilder
{
    protected $connection;
    protected $table;
    protected $query;

    public function __construct(Connection $connection, $table)
    {
        $this->connection = $connection;
        $this->table = $table;
        $this->query = "SELECT * FROM {$table}";
    }

    public function get()
    {
        $statement = $this->connection->getPdo()->prepare($this->query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function where($column, $value, $operator = '=')
    {
        $this->query .= " WHERE {$column} {$operator} ?";
        $this->parameters[] = $value;
        return $this;
    }

    public function insert(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $statement = $this->connection->getPdo()->prepare($query);
        $statement->execute(array_values($data));
        return $statement->rowCount();
    }

    public function update(array $data)
    {
        $setClause = implode(', ', array_map(function ($column) {
            return "{$column} = ?";
        }, array_keys($data)));

        $query = "UPDATE {$this->table} SET {$setClause}";

        if (!empty($this->parameters)) {
            $query .= ' WHERE ' . array_pop($this->parameters);
        }

        $statement = $this->connection->getPdo()->prepare($query);
        $statement->execute(array_merge(array_values($data), $this->parameters));
        return $statement->rowCount();
    }

    public function delete()
    {
        $query = "DELETE FROM {$this->table}";

        if (!empty($this->parameters)) {
            $query .= ' WHERE ' . array_pop($this->parameters);
        }

        $statement = $this->connection->getPdo()->prepare($query);
        $statement->execute($this->parameters);
        return $statement->rowCount();
    }
}

class ConnectionException extends Exception
{
    
}