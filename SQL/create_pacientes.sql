CREATE TABLE pacientes( 
id_paciente INT PRIMARY KEY NOT NULL, 
nombre VARCHAR(20) NOT NULL,
apellidos VARCHAR(50) NOT NULL,
sexo VARCHAR(20) NOT NULL,
nacimiento DATE NOT NULL,
raza VARCHAR(20) NOT NULL,
profesion VARCHAR(50) NOT NULL,
fumador BOOLEAN,
bebedor BOOLEAN,
ultima_modificacion DATE,
carcinógenos BOOLEAN ) ENGINE=InnoDB;

CREATE TABLE enfermedades( 
id_enfermedad INT PRIMARY KEY NOT NULL, 
id_paciente INT NOT NULL,
fecha_primera_consulta DATE NOT NULL,
fecha_diagnostico DATE NOT NULL,
ECOG INT NOT NULL,
T INT NOT NULL,
T_tamano DECIMAL(11,2) NOT NULL,
N INT NOT NULL,
N_afectacion INT NOT NULL,
M VARCHAR(2) NOT NULL,
num_afect_metas VARCHAR(11) NOT NULL,
TNM VARCHAR(4) NOT NULL,
tipo_muestra VARCHAR(50) NOT NULL,
histologia_tipo VARCHAR(50) NOT NULL,
histologia_subtipo VARCHAR(50) NOT NULL,
histologia_grado VARCHAR(50),
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE antecedentes_familiares(
id_antecedente_f INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
familiar VARCHAR(20) NOT NULL,
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE antecedentes_oncologicos(
id_antecedente_o INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
tipo VARCHAR(50) NOT NULL,
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE reevaluaciones(
id_reevaluacion INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
estado VARCHAR(20) NOT NULL,
progresion_localizacion VARCHAR(50),
tipo_tratamiento VARCHAR(20),
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE tratamientos(
id_tratamiento INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
subtipo VARCHAR(20),
dosis INT,
localizacion VARCHAR(20),
fecha_inicio DATE NOT NULL,
fecha_fin DATE,
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE antecedentes_medicos(
id_antecedente_m INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
tipo_antecedente VARCHAR(20) NOT NULL,
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE seguimientos(
id_seguimiento INT PRIMARY KEY NOT NULL,
id_paciente INT NOT NULL,
fecha DATE NOT NULL,
estado VARCHAR(50) NOT NULL,
fallecido_motivo VARCHAR(50),
fecha_fallecimiento DATE,
tratamiento_dirigido BOOLEAN,
FOREIGN KEY (id_paciente) REFERENCES pacientes(id_paciente) ) ENGINE=InnoDB;

CREATE TABLE mestastasis(
id_metastasis INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
localizacion VARCHAR(20) NOT NULL,
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE sintomas(
id_sintoma INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
fecha_inicio DATE,
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE biomarcadores(
id_biomarcador INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
nombre VARCHAR(20) NOT NULL,
tipo VARCHAR(20),
subtipo VARCHAR(20),
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE pruebas_realizadas(
id_prueba INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE tecnicas_realizadas(
id_tecnica INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE otros_tumores(
id_tumor INT PRIMARY KEY NOT NULL,
id_enfermedad INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
FOREIGN KEY (id_enfermedad) REFERENCES enfermedades(id_enfermedad) ) ENGINE=InnoDB;

CREATE TABLE enfermedades_familiar(
id_enfermedad_f INT PRIMARY KEY NOT NULL,
id_antecedente_f INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
FOREIGN KEY (id_antecedente_f) REFERENCES antecedentes_familiares(id_antecedente_f) ) ENGINE=InnoDB;

CREATE TABLE intenciones(
id_intencion INT PRIMARY KEY NOT NULL,
id_tratamiento INT NOT NULL,
ensayo VARCHAR(50),
ensayo_fase INT,
tratamiento_acceso_expandido BOOLEAN,
tratamiento_fuera_indicacion BOOLEAN,
medicacion_extranjera BOOLEAN,
esquema VARCHAR(20),
modo_administracion VARCHAR(20),
tipo_farmaco VARCHAR(20),
numero_ciclos INT,
FOREIGN KEY (id_tratamiento) REFERENCES tratamientos(id_tratamiento) ) ENGINE=InnoDB;

CREATE TABLE farmacos(
id_farmaco INT PRIMARY KEY NOT NULL,
id_intencion INT NOT NULL,
tipo VARCHAR(20) NOT NULL,
FOREIGN KEY (id_intencion) REFERENCES intenciones(id_intencion) ) ENGINE=InnoDB;
