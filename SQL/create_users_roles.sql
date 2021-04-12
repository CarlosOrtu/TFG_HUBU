CREATE TABLE roles( 
id_rol INT PRIMARY KEY NOT NULL, 
descripcion VARCHAR(80) ) ENGINE=InnoDB;

CREATE TABLE usuarios( 
id_ususario int PRIMARY KEY NOT NULL, 
nombre VARCHAR(30)  NOT NULL, 
apellidos VARCHAR(30)  NOT NULL,
email VARCHAR(50)  UNIQUE NOT NULL, 
contrasena VARCHAR(100) NOT NULL, 
id_rol int  NOT NULL, 
FOREIGN KEY (id_rol) REFERENCES Roles(id_rol) ) ENGINE=InnoDB;
