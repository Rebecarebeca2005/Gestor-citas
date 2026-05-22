<?php
require_once __DIR__ . '/../models/user.php';

class AuthController {

   public function register($dato) {

    $nombre = trim($dato['nombre'] ?? ''); //Se obtienen todos los datos y se almacenan en variables (limpiandolos)
    $apellidos = trim($dato['apellidos'] ?? '');
    $email = trim($dato['email'] ?? '');
    $telefono = trim($dato['telefono'] ?? '');
    $password = $dato['password'] ?? '';

    $user = new User(); //Obtenemos al usuario mediante el modal y lo metemos en una variable, así podemos usar sus metodos

    $ok = $user->registrar( //Registramos el user a la BBDD y guardamos la info
        $nombre,
        $apellidos,
        $email,
        $telefono,
        $password
    );
  

     if ($ok) { //Si todo está OK
            header("Location: ?pagina=login&success=Registro realizado correctamente"); //Redirige al usuario al login y añade el mensaje a la URL
            exit;
        }

        header("Location: ?pagina=login&error=No se pudo completar el registro"); //Si ha sido incorrecto, vuelve al login y muestra el mensaje de error
        exit;
       
  
}


   public function login($dato) {

    $email = trim($dato['email'] ?? ''); //Recogemos los datos enviados desde el formulario
    $password = $dato['password'] ?? '';

    if ($email === '' || $password === '') { //Comprobamos espacios vacíos
        header("Location: ?pagina=login&error=Faltan datos"); //Si los hay...
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { //Comprobamos que el email tenga un formato OK
        header("Location: ?pagina=login&error=Email no válido"); //Si no...
        exit;
    }

    $user = new User(); //Obtenemos al usuario mediante el modal y asi accedemos a sus métodos
    $usuario = $user->login($email, $password); //Accedemos al metodo login, comprobamos las credenciales en la BBDD

    if ($usuario) { //Si el user existe y las pass correcta...
        if (session_status() === PHP_SESSION_NONE) { //Comprobamos si la sesión ya esta iniciada
            session_start(); //Sino se crea
        }

        $_SESSION['usuario'] = $usuario; //Guardamos todo el array del usuario
        $_SESSION['usuario_id'] = $usuario['id_usuario']; //el id
        $_SESSION['usuario_nombre'] = $usuario['nombre']; //nombre...
        $_SESSION['usuario_email'] = $usuario['email'];
        $_SESSION['usuario_rol'] = $usuario['rol'];

        $rol = strtolower(trim($usuario['rol'])); //Obtenemos el rol y se convierte en minúsculas
        if ($rol === 'admin') { //si es admin
            header("Location: ?pagina=centroControlAdmin"); //al lado admin
        } else { //sino
            header("Location: ?pagina=centroControl"); //al lado de gente normal
        }
        exit;
    }
    
    header("Location: ?pagina=login&error=Credenciales incorrectas"); //Si no existe el user, correo mal, pass mal...
    exit;
}

public function crearUsuarioAdmin($data) {

    $nombre = trim($data['nombre'] ?? ''); //Limpiamos los datos
    $apellidos = trim($data['apellidos'] ?? '');
    $correo = trim($data['correo'] ?? '');
    $telefono = trim($data['telefono'] ?? '');
    $password = trim($data['password'] ?? '');
    $rol = trim($data['rol'] ?? 'CLIENTE');

    if ( //Si no hay ninguno rellenado
        !$nombre ||
        !$apellidos ||
        !$correo ||
        !$telefono ||
        !$password
    ) {

        return [ //Salta el error
            'ok' => false,
            'msg' => 'Completa todos los campos'
        ];
    }

    $user = new User(); //Añadimos el modal para acceder a las funciones 

    $usuarioExistente = //Creamos una variable y buscamos un usuario que tenga ese correo
        $user->buscarPorCorreo($correo);

    if ($usuarioExistente) {

        return [ //Si existe...
            'ok' => false,
            'msg' => 'El correo ya existe'
        ];
    }

    $passwordHash =
        password_hash( $password, PASSWORD_DEFAULT); //Encriptamos la pass

    $ok = $user->crearUsuarioAdmin(
        $nombre,
        $apellidos,
        $correo,
        $telefono,
        $passwordHash,
        $rol
    );

    if ($ok) { //Si todo está bien...

        return [
            'ok' => true,
            'msg' => 'Usuario creado correctamente'
        ];
    }

    return [ //Sino...
        'ok' => false,
        'msg' => 'Error creando usuario'
    ];
}


}