/* ******************************
    TABLA: persona
****************************** */
INSERT INTO persona (persona_id,nombre,apellido,email,es_docente) VALUES (1,'Victor','Cajes','victor.cajes@uca.edu.py','N');
ALTER SEQUENCE persona_persona_id_seq RESTART WITH 2;

/* ******************************
    TABLA: rol
****************************** */
INSERT INTO rol (rol_id,nombre,descripcion,codigo) VALUES (1,'Administrador del Sistema','Usuarios encargados del mantenimiento del sistema','adm');
INSERT INTO rol (rol_id,nombre,descripcion,codigo) VALUES (2,'Equipo de Encuestadores','Usuarios encargados del mantenimiento de las encuestas','enc');
INSERT INTO rol (rol_id,nombre,descripcion,codigo) VALUES (3,'Director de Departamento','Usuarios que puedan visualizar informes especiales del departamento','dir');
INSERT INTO rol (rol_id,nombre,descripcion,codigo) VALUES (4,'Secretaria Academica','Usuarios encargados de realizar las encuestas','sec');
ALTER SEQUENCE rol_rol_id_seq RESTART WITH 5;

/* ******************************
    TABLA: usuario
****************************** */
INSERT INTO usuario (usuario_id,persona_id,usuario,contrasena,activo) VALUES (1,1,'vcajes','f8b41bbd8611ed225ba3b0d7726c164c41734af3','S');
ALTER SEQUENCE usuario_usuario_id_seq RESTART WITH 2;

/* ******************************
    TABLA: usuario_perm
****************************** */
INSERT INTO usuario_perm (usuario_perm_id,usuario_id,rol_id) VALUES (1,1,1);
ALTER SEQUENCE usuario_perm_usuario_perm_id_seq RESTART WITH 2;

/* ******************************
    TABLA: periodo
****************************** */
INSERT INTO periodo (periodo_id,periodo) VALUES (1,'1er. Semestre');
INSERT INTO periodo (periodo_id,periodo) VALUES (2,'2do. Semestre');
ALTER SEQUENCE periodo_periodo_id_seq RESTART WITH 3;

/* ******************************
    TABLA: facultad
****************************** */
INSERT INTO facultad (facultad_id,nombre,codigo) VALUES (1,'Facultad de Ciencias y Tecnologia','CyT');
ALTER SEQUENCE facultad_facultad_id_seq RESTART WITH 2;
