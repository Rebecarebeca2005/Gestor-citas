<?php

class Database {

    // private $host = "sql312.infinityfree.com";
    // private $dbname = "if0_41733231_citas";
    // private $user = "if0_41733231";
    // private $password = "amparoampa1010";

private $host = "localhost";
private $dbname = "gestor_citas";
private $user = "root";
private $password = ""; 

    public function connect() {

        try {
        $conn = new PDO(
            "mysql:host={$this->host};port=3307;dbname={$this->dbname};charset=utf8",
            $this->user,
            $this->password
        );

            $conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            // =========================
            // ZONA HORARIA ESPAÑA
            // =========================
            $conn->exec("SET time_zone = '+02:00'");

            return $conn;

        } catch (PDOException $e) {

            die(
                "Error de conexión: "
                . $e->getMessage()
            );
        }
    }
}

?>