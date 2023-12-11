

-- script base de datos senderismo


create database senderismo;

use senderismo;
	

CREATE TABLE `usuarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nombre` VARCHAR(100) NOT NULL,
    `nombre_usuario` VARCHAR(50) NOT NULL,
    `contrasena` VARCHAR(255) NOT NULL,
    `rol` ENUM('admin','usur') NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `nombre_usuario` (`nombre_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear la tabla rutas_comentarios
CREATE TABLE `rutas_comentarios` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_ruta` INT(11) DEFAULT NULL,
    `nombre` VARCHAR(50) NOT NULL,
    `contenido` TEXT DEFAULT NULL,
    `fecha` DATE DEFAULT NULL,
    `id_usuario` INT(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Crear la tabla rutas
CREATE TABLE `rutas` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `titulo` VARCHAR(255) NOT NULL,
    `descripcion` TEXT DEFAULT NULL,
    `desnivel` INT(11) DEFAULT NULL,
    `distancia` FLOAT DEFAULT NULL,
    `notas` TEXT DEFAULT NULL,
    `dificultad` VARCHAR(20) DEFAULT NULL,
    `id_usuario` INT(11) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Agregar claves foráneas
ALTER TABLE `rutas_comentarios`
    ADD CONSTRAINT `fk_ruta_comentario` FOREIGN KEY (`id_ruta`) REFERENCES `rutas` (`id`);

ALTER TABLE `rutas_comentarios`
    ADD CONSTRAINT `fk_usuario_comentario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

ALTER TABLE `rutas`
    ADD CONSTRAINT `fk_usuario_ruta` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
