<?php
require_once __DIR__ . '/../config/database.php';

class User {

    private $dato;

    public function __construct() {
        $db = new Database();
        $this->dato = $db->connect();
    }

    public function registrar($nombre, $apellidos, $email, $telefono, $password) {

        // Encriptar contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, apellidos, email, telefono, password)
                VALUES (:nombre, :apellidos, :email, :telefono, :password)";

        $stmt = $this->dato->prepare($sql);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':telefono' => $telefono,
            ':password' => $passwordHash
        ]);
    }

    public function login($email, $password) {

        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->dato->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    
}

