
USE gestor_citas;

-- TABLA USUARIOS
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN', 'CLIENTE') DEFAULT 'CLIENTE'
);


-- TABLA SERVICIOS
CREATE TABLE servicios (
    id_servicio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    duracion INT NOT NULL,
    precio DECIMAL(6,2),
    activo BOOLEAN DEFAULT TRUE
);


-- TABLA DISPONIBILIDAD
CREATE TABLE disponibilidad (
    id_disponibilidad INT AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    disponible BOOLEAN DEFAULT TRUE
);

-- TABLA CITAS
CREATE TABLE citas (
    id_cita INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_servicio INT NOT NULL,
    id_disponibilidad INT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    estado ENUM('ACTIVA','CANCELADA') DEFAULT 'ACTIVA',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- RELACIONES
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_servicio) REFERENCES servicios(id_servicio),
    FOREIGN KEY (id_disponibilidad) REFERENCES disponibilidad(id_disponibilidad)
);

INSERT INTO usuarios (nombre, apellidos, email, telefono, password, rol) VALUES
('Admin', 'Principal', 'admin@empresa.com', '600123456', 'AdminEmpresa12.', 'admin'),
('Carlos', 'Martín Ruiz', 'carlos@email.com', '611234567', '$2y$10$ejemplohash2', 'CLIENTE'),
('Laura', 'Sánchez Díaz', 'laura@email.com', '622345678', '$2y$10$ejemplohash3', 'CLIENTE'),
('David', 'Fernández Pérez', 'david@email.com', '633456789', '$2y$10$ejemplohash4', 'CLIENTE');

INSERT INTO servicios (nombre, descripcion, duracion, precio, activo) VALUES
('Corte de pelo', 'Corte básico de cabello', 30, 12.00, TRUE),
('Tinte', 'Coloración completa del cabello', 90, 35.00, TRUE),
('Lavado y peinado', 'Lavado con peinado incluido', 20, 8.00, TRUE),
('Tratamiento capilar', 'Hidratación y cuidado del cabello', 45, 20.00, TRUE);
