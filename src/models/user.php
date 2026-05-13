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

public function eliminarUsuario($id)
{
    try {
        $this->dato->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->dato->beginTransaction();

        $stmt1 = $this->dato->prepare("DELETE FROM citas WHERE id_usuario = ?");
        $stmt1->execute([$id]);

        $stmt2 = $this->dato->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
        $stmt2->execute([$id]);

        $filas = $stmt2->rowCount();

        $this->dato->commit();

        return $filas;

    } catch (Exception $e) {
        $this->dato->rollBack();
        return "ERROR: " . $e->getMessage();
    }
}

public function buscarPorCorreo($email) {

    $sql = "SELECT *
            FROM usuarios
            WHERE email = :email";

    $stmt = $this->dato->prepare($sql);

    $stmt->execute([
        ':email' => $email
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function crearUsuario(
    $nombre,
    $email,
    $password,
    $rol
) {

    $sql = "INSERT INTO usuarios
            (
                nombre,
                email,
                password,
                rol
            )
            VALUES
            (
                :nombre,
                :email,
                :password,
                :rol
            )";

    $stmt = $this->dato->prepare($sql);

    return $stmt->execute([
        ':nombre' => $nombre,
        ':email' => $email,
        ':password' => $password,
        ':rol' => $rol
    ]);
}

public function crearUsuarioAdmin(
    $nombre,
    $apellidos,
    $email,
    $telefono,
    $password,
    $rol
) {

    $sql = "
        INSERT INTO usuarios
        (
            nombre,
            apellidos,
            email,
            telefono,
            password,
            rol
        )
        VALUES
        (
            :nombre,
            :apellidos,
            :email,
            :telefono,
            :password,
            :rol
        )
    ";

    $stmt = $this->dato->prepare($sql);

    return $stmt->execute([

        ':nombre' => $nombre,
        ':apellidos' => $apellidos,
        ':email' => $email,
        ':telefono' => $telefono,
        ':password' => $password,
        ':rol' => $rol
    ]);
}
    
}

