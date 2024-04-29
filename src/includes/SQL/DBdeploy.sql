-- Eliminar la base de datos EasyCompraDB si existe
DROP DATABASE IF EXISTS EasyCompraDB;

-- Crear la base de datos EasyCompraDB
CREATE DATABASE EasyCompraDB;

-- Utilizar la base de datos EasyCompraDB
USE EasyCompraDB;

-- Tabla para almacenar información de los usuarios
CREATE TABLE Usuario (
  id INTEGER PRIMARY KEY AUTO_INCREMENT, -- Identificador único del usuario
  username VARCHAR(255), -- Nombre de usuario
  pass VARCHAR(255), -- Contraseña del usuario
  correo VARCHAR(255), -- Correo electrónico del usuario
  descripcion VARCHAR(255), -- Descripción del usuario
  administrador BOOLEAN, -- Indicador de si el usuario es administrador
  typo VARCHAR(255), -- Tipo de archivo de la imagen
  blobi longblob -- Datos binarios de la imagen

);

-- Tabla para almacenar información de los productos
CREATE TABLE Producto (
  id INTEGER PRIMARY KEY AUTO_INCREMENT, -- Identificador único del producto
  dueno_id INTEGER REFERENCES Usuario(id), -- ID del dueño del producto, clave foránea de Usuario
  name VARCHAR(255), -- Nombre del producto
  descripcion VARCHAR(255), -- Descripción del producto
  precio DECIMAL, -- Precio del producto
  categoria int, -- id de la categoría del producto
  typo VARCHAR(255), -- Tipo de archivo de la imagen
  blobi longblob -- Datos binarios de la imagen
);

-- Tabla para almacenar información de las categorías de productos
CREATE TABLE Categoria (
  id INTEGER PRIMARY KEY AUTO_INCREMENT, -- Identificador único de la categoría
  nombre VARCHAR(255) -- Nombre de la categoría
);

-- Verificar si el usuario existe ya
SELECT EXISTS(SELECT 1 FROM mysql.user WHERE user = 'ReaderWriter' AND host = 'localhost') AS user_exists;

-- Si el usuario no existe, entonces crearlo
CREATE USER IF NOT EXISTS 'ReaderWriter'@'localhost' IDENTIFIED BY 'ReaderWriterPassword';

-- Conceder privilegios de lectura y escritura sobre la base de datos EasyChangeDB a este usuario
GRANT SELECT, INSERT, UPDATE, DELETE ON EasyCompraDB.* TO 'ReaderWriter'@'localhost';

INSERT INTO Categoria (nombre)
VALUES 
  ('Electrónica'),
  ('Ropa'),
  ('Hogar'),
  ('Juguetes'),
  ('Informática'),
  ('Libros'),
  ('Deportes'),
  ('Alimentación'),
  ('Accesorios'),
  ('Salud y belleza');

