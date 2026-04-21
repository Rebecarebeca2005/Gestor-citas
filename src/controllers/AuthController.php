<?php
require_once __DIR__ . '/../models/user.php';

class AuthController {

    public function register($data) {

        // Validaciones básicas
        if (empty($data['nombre']) || empty($data['email']) || empty($data['password'])) {
            return "Faltan datos";
        }

        if ($data['password'] !== $data['password2']) {
            return "Las contraseñas no coinciden";
        }

        $user = new User();

        $ok = $user->registrar(
            $data['nombre'],
            $data['email'],
            $data['telefono'],
            $data['password']
        );

        return $ok ? "Registrado correctamente" : "Error al registrar";
    }

    public function login($data) {

        $user = new User();

        $usuario = $user->login($data['email'], $data['password']);

        if ($usuario) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            return "Login correcto";
        }

        return "Credenciales incorrectas";
    }
}