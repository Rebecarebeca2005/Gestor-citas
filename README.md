# Nexo - Gestor de Citas

Nexo es una aplicación web desarrollada como Proyecto de Fin de Grado (TFG) para la gestión de citas y reservas online. La plataforma permite a los usuarios solicitar, consultar, modificar y cancelar citas de forma sencilla, mientras que los administradores disponen de herramientas para gestionar usuarios y estadísticas del sistema.

---

## Características principales

### Usuarios

- Registro de cuenta.
- Inicio de sesión seguro.
- Gestión del perfil personal.
- Solicitud de nuevas citas.
- Consulta de citas programadas.
- Modificación de reservas.
- Cancelación de citas.

### Administración

- Gestión de usuarios.
- Consulta de estadísticas.
- Control de citas activas y canceladas.
- Visualización del calendario de reservas.

---

## Tecnologías utilizadas

### Frontend

- HTML5
- CSS3
- JavaScript

### Backend

- PHP 8

### Base de datos

- MySQL

### Herramientas

- XAMPP
- phpMyAdmin
- Visual Studio Code
- Docker
- Git y GitHub

---

## Estructura del proyecto

```text
gestor_citas/
│
├── assets/
│   ├── css/
│   ├── fonts/
│   ├── img/
│   └── js/
│
├── docker/
│
├── sesiones/
│
├── src/
│   ├── config/
│   ├── controllers/
│   ├── models/
│   └── views/
│
├── .gitignore
├── generar_citas.php
├── generar_disponibilidad.php
├── index.php
└── README.md
```

### Descripción de directorios

| Directorio | Descripción |
|------------|------------|
| `assets/css` | Hojas de estilo de la aplicación. |
| `assets/fonts` | Tipografías utilizadas en la interfaz. |
| `assets/img` | Recursos gráficos e imágenes del sistema. |
| `assets/js` | Scripts JavaScript utilizados en la aplicación. |
| `docker` | Archivos de configuración para el despliegue mediante Docker. |
| `sesiones` | Gestión y almacenamiento de información relacionada con las sesiones de usuario. |
| `src/config` | Configuración general de la aplicación y conexión con la base de datos. |
| `src/controllers` | Controladores encargados de gestionar la lógica de negocio y las peticiones. |
| `src/models` | Modelos responsables del acceso y manipulación de datos. |
| `src/views` | Vistas encargadas de la presentación de la interfaz de usuario. |
| `index.php` | Punto de entrada principal de la aplicación. |
| `generar_citas.php` | Script encargado de la generación automática de citas. |
| `generar_disponibilidad.php` | Script encargado de generar la disponibilidad de horarios. |