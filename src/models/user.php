<?php
require_once __DIR__ . '/../config/database.php';

class User {

    private $dato; //Para poder obtener los datos de las variables

    public function __construct() {
        $db = new Database();
        $this->dato = $db->connect();
    }

    public function registrar($nombre, $apellidos, $email, $telefono, $password) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT); //Encriptamos la contraseña

        $sql = "INSERT INTO usuarios (nombre, apellidos, email, telefono, password)
                VALUES (:nombre, :apellidos, :email, :telefono, :password)"; //Preparamos al consulta de inserción de datos

        $stmt = $this->dato->prepare($sql); //Preparamos la consulta antes de ejecutarla ($dato es la conexión a la BBDD)

        return $stmt->execute([ //Asigna los valores recibidos a los parámetros de la consulta y la ejecuta
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':telefono' => $telefono,
            ':password' => $passwordHash
        ]);
    }

    public function login($email, $password) {

        $sql = "SELECT * FROM usuarios WHERE email = :email"; //Buscamos un usuario cuyo email sea el que se acaba de introducir
        $stmt = $this->dato->prepare($sql); //Preparamos la consulta
        $stmt->execute([':email' => $email]); //Ejecutamos la consulta, sustituye :email por $email 

        $user = $stmt->fetch(PDO::FETCH_ASSOC); //Obtenemos los datos del usuario encontrado como array asociativo ($user['nombre']) Si no fuera asociativo saldría así ($user[0])

        if ($user && password_verify($password, $user['password'])) { //Si existe un usuario con ese email y compara que sea igual la pass del user con el hash (12345 > $2y$10$a)
            return $user; //Login correcto, devuelve datos del usuario
        }

        return false;
    }

public function eliminarUsuario($id) {

    
    $stmt = $this->dato->prepare("
        DELETE FROM citas
        WHERE id_usuario = :id
    "); //Borramos las citas primero, ya que estan asociadas

    $stmt->execute([
        ':id' => $id
    ]);

    
    $stmt = $this->dato->prepare(" 
        DELETE FROM usuarios
        WHERE id_usuario = :id
    "); //Borramos al usuario

    return $stmt->execute([
        ':id' => $id
    ]);
}

public function buscarPorCorreo($email) {

    $sql = "SELECT *
            FROM usuarios
            WHERE email = :email"; //Buscamos el email

    $stmt = $this->dato->prepare($sql); //preparamos la consulta

    $stmt->execute([
        ':email' => $email //Se ejecuta
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC); //Devolvemos el array asociativo, el valor del email
}

public function crearUsuario( //Función para crear el usuario
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
    "; //Creamos el usuario

    $stmt = $this->dato->prepare($sql); //Se prepara

    return $stmt->execute([ //lo ejecutamos

        ':nombre' => $nombre,
        ':apellidos' => $apellidos,
        ':email' => $email,
        ':telefono' => $telefono,
        ':password' => $password,
        ':rol' => $rol
    ]);
}
    
}

