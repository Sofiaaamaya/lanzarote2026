#\. /var/www/html/lanzarote.lan/tablas/create_table_usuarios.sql



DROP TABLE personas;

CREATE TABLE personas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    apellidos   VARCHAR(100) NOT NULL,
    email       VARCHAR(150) UNIQUE,
    tipo        CHAR(01) NOT NULL,        
);




DROP TABLE cursos;

CREATE TABLE cursos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre_grado    VARCHAR(50) NOT NULL,           
    curso_numero    INT NOT NULL,                   
    letra           CHAR(1) NOT NULL,              
    UNIQUE(nombre_grado, curso_numero, letra));
    
    


DROP TABLE modulos;

CREATE TABLE modulos (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,              
    siglas          VARCHAR(10) NOT NULL,               
    color           VARCHAR(7) NOT NULL UNIQUE,        
    curso_asignado  INT NOT NULL,                      
    FOREIGN KEY (curso_asignado) REFERENCES cursos(id)

);



DROP TABLE aulas;

CREATE TABLE aulas (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(50) NOT NULL UNIQUE, 
    capacidad   INT NULL                     
    
);



DROP TABLE horarios;

CREATE TABLE horarios (
    id                  INT AUTO_INCREMENT PRIMARY KEY,
    dia                 CHAR(01) NOT NULL,
    hora_inicio         TIME NOT NULL,
    hora_fin            TIME NOT NULL,
    id_modulo INT NOT NULL,     
    id_profesor INT NOT NULL,    
    id_aula INT NULL,            
    FOREIGN KEY (id_modulo) REFERENCES modulos(id),
    FOREIGN KEY (id_profesor) REFERENCES personas(id),
    FOREIGN KEY (id_aula) REFERENCES aulas(id) 


);

