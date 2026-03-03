CREATE DATABASE sistema_web;
USE sistema_web;

CREATE TABLE tipo_usuario (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) NOT NULL
);

CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    correo VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    id_tipo INT,
    FOREIGN KEY (id_tipo) REFERENCES tipo_usuario(id_tipo)
);

CREATE TABLE bitacora (
    id_registro INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    fecha_acceso DATETIME DEFAULT CURRENT_TIMESTAMP,
    direccion_ip VARCHAR(45),
    exito BOOLEAN,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
);

-- INSERCIÓN DE DATOS DE PRUEBA --

-- Insertar Tipos de Usuario
INSERT INTO tipo_usuario (nombre_tipo) VALUES 
('Administrador'),
('Operador'),
('Invitado');

-- Insertar Usuarios de Prueba
INSERT INTO usuario (nombre_completo, correo, password, id_tipo) VALUES 
('Josue Administrador', 'admin@sistema.com', 'admin123', 1),
('Operador Pruebas', 'operador@sistema.com', 'opera456', 2);