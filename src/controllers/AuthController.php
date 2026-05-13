<?php
require_once __DIR__ . '/../models/user.php';

class AuthController {

   public function register($dato) {

    $nombre = trim($dato['nombre'] ?? '');
    $apellidos = trim($dato['apellidos'] ?? '');
    $email = trim($dato['email'] ?? '');
    $telefono = trim($dato['telefono'] ?? '');
    $password = $dato['password'] ?? '';

    $user = new User();

    $ok = $user->registrar(
        $nombre,
        $apellidos,
        $email,
        $telefono,
        $password
    );
   

     if ($ok) {
            header("Location: ?pagina=login&success=Registro realizado correctamente");
            exit;
        }

        header("Location: ?pagina=login&error=No se pudo completar el registro");
        exit;
}


   public function login($dato) {

    $email = trim($dato['email'] ?? '');
    $password = $dato['password'] ?? '';

    if ($email === '' || $password === '') {
        header("Location: ?pagina=login&error=Faltan datos");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ?pagina=login&error=Email no válido");
        exit;
    }

    $user = new User();
    $usuario = $user->login($email, $password);

    if ($usuario) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['usuario'] = $usuario;
        $_SESSION['usuario_id'] = $usuario['id_usuario']; 
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        $rol = strtolower(trim($usuario['rol']));
        if ($rol === 'admin') {
            header("Location: ?pagina=centroControlAdmin");
        } else {
            header("Location: ?pagina=centroControl");
        }
        exit;
    }
    
    header("Location: ?pagina=login&error=Credenciales incorrectas");
    exit;
}

public function crearUsuarioAdmin($data) {

    $nombre = trim($data['nombre'] ?? '');
    $apellidos = trim($data['apellidos'] ?? '');
    $correo = trim($data['correo'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $password = trim($data['password'] ?? '');
    $rol = trim($data['rol'] ?? 'CLIENTE');

    if (
        !$nombre ||
        !$apellidos ||
        !$correo ||
        !$telefono ||
        !$password
    ) {

        return [
            'ok' => false,
            'msg' => 'Completa todos los campos'
        ];
    }

    $user = new User();

    $usuarioExistente =
        $user->buscarPorCorreo($correo);

    if ($usuarioExistente) {

        return [
            'ok' => false,
            'msg' => 'El correo ya existe'
        ];
    }

    $passwordHash =
        password_hash(
            $password,
            PASSWORD_DEFAULT
        );

    $ok = $user->crearUsuarioAdmin(
        $nombre,
        $apellidos,
        $correo,
        $telefono,
        $passwordHash,
        $rol
    );

    if ($ok) {

        return [
            'ok' => true,
            'msg' => 'Usuario creado correctamente'
        ];
    }

    return [
        'ok' => false,
        'msg' => 'Error creando usuario'
    ];
}


}