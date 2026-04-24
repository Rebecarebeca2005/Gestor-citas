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
    session_start();
    $_SESSION['usuario'] = $usuario;

    return $usuario;
}

    header("Location: ?pagina=login&error=Credenciales incorrectas");
    exit;
}


}