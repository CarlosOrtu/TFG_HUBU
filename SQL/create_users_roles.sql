CREATE TABLE roles( 
id_role INT PRIMARY KEY NOT NULL, 
description VARCHAR(80) ) ENGINE=InnoDB;

CREATE TABLE users( 
id_user int PRIMARY KEY NOT NULL, 
name VARCHAR(30)  NOT NULL, 
surname VARCHAR(30)  NOT NULL,
email VARCHAR(50)  UNIQUE NOT NULL, 
password VARCHAR(100) NOT NULL, 
id_role int  NOT NULL, 
FOREIGN KEY (id_role) REFERENCES Roles(id_role) ) ENGINE=InnoDB;
