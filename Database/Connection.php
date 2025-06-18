<?php

namespace Database;

class Connection
{
    const HOST = ' theaterappserverdb.mysql.database.azure.com';
    const DB_NAME = ' theaterappserverdb';
    const USERNAME = 'mohamed';
    const PASSWORD = 'momo2025#$';

    protected $connection;

    public function __construct()
    {
        $this->connection = new \PDO('mysql:host=' . self::HOST . ';dbname=' . self::DB_NAME, self::USERNAME, self::PASSWORD);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function destroy()
    {
        $this->connection = null;
    }
}

?>
