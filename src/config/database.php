<?php

class Database
{
    private $host;
    private $dbname;
    private $user;
    private $password;
    private $port;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->dbname = getenv('DB_NAME') ?: 'gestor_citas';
        $this->user = getenv('DB_USER') ?: 'root';
        $this->password = getenv('DB_PASS') ?: '';
        $this->port = getenv('DB_PORT') ?: '3307';
    }

    public function connect()
    {
        try {

            $conn = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8",
                $this->user,
                $this->password
            );

            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            $conn->exec("SET time_zone = '+02:00'");

            return $conn;

        } catch (PDOException $e) {

            die(
                "Error de conexión: " .
                $e->getMessage()
            );
        }
    }
}