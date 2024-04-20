<?php 

require_once('DatabaseManager.php');
require_once('Connection.php');
require_once('QueryBuilder.php');

// Create a new instance of the DatabaseManager
$db = new DatabaseManager([
    'config' => [
        'database.default' => 'mysql',
        'database.connections' => [
            'mysql' => [
                'driver' => 'mysql',
                'host' => 'localhost',
                'database' => 'mydb',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8mb4',
            ],
        ],
    ],
]);

// Get a connection instance
$connection = $db->connection('mysql');

// Use the QueryBuilder to execute queries
$users = $connection->table('users')->where('active', 1)->get();

$newUser = $connection->table('users')->insert([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'active' => 1,
]);

$updatedRows = $connection->table('users')
    ->where('id', 1)
    ->update([
        'name' => 'Jane Doe',
    ]);

$deletedRows = $connection->table('users')
    ->where('id', 2)
    ->delete();