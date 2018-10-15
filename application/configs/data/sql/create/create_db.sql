/* ---------------------------------------------------------------------- */
/* Script generated with: DeZign for Databases v6.3.4                     */
/* Target DBMS:           PostgreSQL 9                                    */
/* Project file:          encuestas_final.dez                             */
/* Project name:                                                          */
/* Author:                                                                */
/* Script type:           Database creation script                        */
/* Created on:            2013-03-06 13:02                                */
/* ---------------------------------------------------------------------- */


/* ---------------------------------------------------------------------- */
/* Sequences                                                              */
/* ---------------------------------------------------------------------- */

CREATE SEQUENCE public.escala_escala_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.escala_valor_escala_valor_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.rol_rol_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.persona_persona_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.docente_docente_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.asignatura_asignatura_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.enc_particular_enc_particular_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.periodo_lectivo_periodo_lectivo_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.facultad_facultad_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.respuesta_respuesta_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.encuesta_encuesta_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.usuario_usuario_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.usuario_perm_usuario_perm_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.departamento_departamento_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.docente_dpto_docente_dpto_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.carrera_carrera_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.categoria_categoria_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.pregunta_pregunta_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE SEQUENCE public.pregunta_sm_pregunta_sm_id_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

/* ---------------------------------------------------------------------- */
/* Tables                                                                 */
/* ---------------------------------------------------------------------- */

/* ---------------------------------------------------------------------- */
/* Add table "rol"                                                        */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.rol (
    rol_id INTEGER DEFAULT nextval('rol_rol_id_seq')  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    descripcion TEXT,
    codigo CHARACTER(3),
    CONSTRAINT pk_rol PRIMARY KEY (rol_id)
);

COMMENT ON COLUMN public.rol.rol_id IS 'Encuestador : Crean las encuestas. Luego de recabar las respuestas, generan ciertos informes. Director : Pueden ver informes de dpto y de cada profesor. Docente : Pueden ver sus propios informes. Son usados en las encuestas. Encuestado : Evaluan a los profesores en las encuestas. Secretaria : Acercan las encuestas a los encuestados. Administrador : Gestiona los permisos del sistema.';

/* ---------------------------------------------------------------------- */
/* Add table "escala"                                                     */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.escala (
    escala_id INTEGER DEFAULT nextval('escala_escala_id_seq')  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    descripcion TEXT,
    CONSTRAINT pk_escala PRIMARY KEY (escala_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "facultad"                                                   */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.facultad (
    facultad_id INTEGER DEFAULT nextval('facultad_facultad_id_seq')  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    codigo CHARACTER VARYING(10)  NOT NULL,
    CONSTRAINT pk_facultad PRIMARY KEY (facultad_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "persona"                                                    */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.persona (
    persona_id INTEGER DEFAULT nextval('persona_persona_id_seq')  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    apellido CHARACTER VARYING(200)  NOT NULL,
    email TEXT,
    es_docente CHARACTER(1),
    CONSTRAINT pk_persona PRIMARY KEY (persona_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "encuesta"                                                   */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.encuesta (
    encuesta_id INTEGER DEFAULT nextval('encuesta_encuesta_id_seq')  NOT NULL,
    escala_id INTEGER  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    descripcion TEXT,
    fecha DATE  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    CONSTRAINT pk_encuesta PRIMARY KEY (encuesta_id)
);

COMMENT ON COLUMN public.encuesta.fecha IS 'Fecha de Creacion o ultima modificacion de la encuesta';

/* ---------------------------------------------------------------------- */
/* Add table "docente"                                                    */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.docente (
    docente_id INTEGER DEFAULT nextval('docente_docente_id_seq')  NOT NULL,
    persona_id INTEGER  NOT NULL,
    codigo CHARACTER VARYING(10)  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    CONSTRAINT pk_docente PRIMARY KEY (docente_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "periodo"                                                    */
/* ---------------------------------------------------------------------- */

CREATE TABLE periodo (
    periodo_id SERIAL  NOT NULL,
    periodo CHARACTER VARYING(100)  NOT NULL,
    CONSTRAINT PK_periodo PRIMARY KEY (periodo_id)
);

COMMENT ON COLUMN periodo.periodo IS '1er Semestre 2do Semestre';

/* ---------------------------------------------------------------------- */
/* Add table "departamento"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.departamento (
    departamento_id INTEGER DEFAULT nextval('departamento_departamento_id_seq')  NOT NULL,
    facultad_id INTEGER  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    codigo CHARACTER VARYING(10)  NOT NULL,
    CONSTRAINT pk_departamento PRIMARY KEY (departamento_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "periodo_lectivo"                                            */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.periodo_lectivo (
    periodo_lectivo_id INTEGER DEFAULT nextval('periodo_lectivo_periodo_lectivo_id_seq')  NOT NULL,
    periodo_id INTEGER  NOT NULL,
    anho_lectivo CHARACTER VARYING(4)  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    CONSTRAINT pk_periodo_lectivo PRIMARY KEY (periodo_lectivo_id)
);

COMMENT ON COLUMN public.periodo_lectivo.anho_lectivo IS '2012, 2013, 2014, etc.';

/* ---------------------------------------------------------------------- */
/* Add table "escala_valor"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.escala_valor (
    escala_valor_id INTEGER DEFAULT nextval('escala_valor_escala_valor_id_seq')  NOT NULL,
    escala_id INTEGER  NOT NULL,
    valor INTEGER  NOT NULL,
    descripcion TEXT  NOT NULL,
    CONSTRAINT pk_escala_valor PRIMARY KEY (escala_valor_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "usuario"                                                    */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.usuario (
    usuario_id INTEGER DEFAULT nextval('usuario_usuario_id_seq')  NOT NULL,
    persona_id INTEGER  NOT NULL,
    usuario CHARACTER VARYING(200)  NOT NULL,
    contrasena CHARACTER VARYING(40)  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    CONSTRAINT pk_usuario PRIMARY KEY (usuario_id)
);

COMMENT ON TABLE public.usuario IS 'Usuarios del Sistema. Se loguean para realizar diversas acciones.';

/* ---------------------------------------------------------------------- */
/* Add table "usuario_perm"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.usuario_perm (
    usuario_perm_id INTEGER DEFAULT nextval('usuario_perm_usuario_perm_id_seq')  NOT NULL,
    usuario_id INTEGER  NOT NULL,
    rol_id INTEGER  NOT NULL,
    CONSTRAINT pk_usuario_perm PRIMARY KEY (usuario_perm_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "carrera"                                                    */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.carrera (
    carrera_id INTEGER DEFAULT nextval('carrera_carrera_id_seq')  NOT NULL,
    departamento_id INTEGER  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    codigo CHARACTER VARYING(10)  NOT NULL,
    CONSTRAINT pk_carrera PRIMARY KEY (carrera_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "docente_dpto"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.docente_dpto (
    docente_dpto_id INTEGER DEFAULT nextval('docente_dpto_docente_dpto_id_seq')  NOT NULL,
    docente_id INTEGER  NOT NULL,
    departamento_id INTEGER  NOT NULL,
    CONSTRAINT pk_docente_dpto PRIMARY KEY (docente_dpto_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "asignatura"                                                 */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.asignatura (
    asignatura_id INTEGER DEFAULT nextval('asignatura_asignatura_id_seq')  NOT NULL,
    carrera_id INTEGER  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    codigo CHARACTER VARYING(20)  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    CONSTRAINT pk_asignatura PRIMARY KEY (asignatura_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "enc_particular"                                             */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.enc_particular (
    enc_particular_id INTEGER DEFAULT nextval('enc_particular_enc_particular_id_seq')  NOT NULL,
    periodo_lectivo_id INTEGER  NOT NULL,
    encuesta_id INTEGER  NOT NULL,
    asignatura_id INTEGER  NOT NULL,
    docente_id INTEGER  NOT NULL,
    identificador CHARACTER(40)  NOT NULL,
    activo CHARACTER(1)  NOT NULL,
    estado_informe CHARACTER(1) NOT NULL,
    CONSTRAINT pk_enc_particular PRIMARY KEY (enc_particular_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "respuesta"                                                  */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.respuesta (
    respuesta_id INTEGER DEFAULT nextval('respuesta_respuesta_id_seq')  NOT NULL,
    enc_particular_id INTEGER  NOT NULL,
    fecha DATE  NOT NULL,
    CONSTRAINT pk_respuesta PRIMARY KEY (respuesta_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "pregunta"                                                   */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.pregunta (
    pregunta_id INTEGER DEFAULT nextval('pregunta_pregunta_id_seq')  NOT NULL,
    encuesta_id INTEGER,
    categoria_id INTEGER,
    pregunta TEXT  NOT NULL,
    descripcion TEXT,
    tipo CHARACTER(1)  NOT NULL,
    es_obligatoria CHARACTER(1)  NOT NULL,
    opcion_multiple CHARACTER(1),
    orden INTEGER  NOT NULL,
    CONSTRAINT pk_pregunta PRIMARY KEY (pregunta_id)
);

COMMENT ON COLUMN public.pregunta.tipo IS 'A = Pregunta Abierta E = Pregunta con escala S = Seleccion Multiple';

COMMENT ON COLUMN public.pregunta.opcion_multiple IS 'Indica si la pregunta puede tener varias respuestas o solo 1 de las opciones de la seleccion multiple. Usar solo en caso de preguntas de seleccion multiple';

/* ---------------------------------------------------------------------- */
/* Add table "pregunta_sm"                                                */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.pregunta_sm (
    pregunta_sm_id INTEGER DEFAULT nextval('pregunta_sm_pregunta_sm_id_seq')  NOT NULL,
    pregunta_id INTEGER  NOT NULL,
    nombre CHARACTER VARYING(200)  NOT NULL,
    valor INTEGER,
    CONSTRAINT pk_pregunta_sm PRIMARY KEY (pregunta_sm_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "categoria"                                                  */
/* ---------------------------------------------------------------------- */

CREATE TABLE public.categoria (
    categoria_id INTEGER DEFAULT nextval('categoria_categoria_id_seq')  NOT NULL,
    encuesta_id INTEGER,
    categoria_padre INTEGER,
    nombre CHARACTER VARYING(200)  NOT NULL,
    descripcion TEXT,
    orden INTEGER  NOT NULL,
    CONSTRAINT pk_categoria PRIMARY KEY (categoria_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "resp_detalle"                                               */
/* ---------------------------------------------------------------------- */

CREATE TABLE resp_detalle (
    resp_detalle_id SERIAL  NOT NULL,
    respuesta_id INTEGER  NOT NULL,
    pregunta_id INTEGER  NOT NULL,
    escala_valor_id INTEGER,
    respuesta TEXT,
    CONSTRAINT PK_resp_detalle PRIMARY KEY (resp_detalle_id)
);

/* ---------------------------------------------------------------------- */
/* Add table "opcion_elegida"                                             */
/* ---------------------------------------------------------------------- */

CREATE TABLE opcion_elegida (
    opcion_elegida_id SERIAL  NOT NULL,
    pregunta_sm_id INTEGER  NOT NULL,
    resp_detalle_id INTEGER  NOT NULL,
    CONSTRAINT PK_opcion_elegida PRIMARY KEY (opcion_elegida_id)
);

/* ---------------------------------------------------------------------- */
/* Foreign key constraints                                                */
/* ---------------------------------------------------------------------- */

ALTER TABLE public.respuesta ADD CONSTRAINT enc_particular_respuesta 
    FOREIGN KEY (enc_particular_id) REFERENCES public.enc_particular (enc_particular_id);

ALTER TABLE public.departamento ADD CONSTRAINT facultad_departamento 
    FOREIGN KEY (facultad_id) REFERENCES public.facultad (facultad_id);

ALTER TABLE public.periodo_lectivo ADD CONSTRAINT periodo_periodo_lectivo 
    FOREIGN KEY (periodo_id) REFERENCES periodo (periodo_id);

ALTER TABLE public.pregunta ADD CONSTRAINT encuesta_pregunta 
    FOREIGN KEY (encuesta_id) REFERENCES public.encuesta (encuesta_id);

ALTER TABLE public.pregunta ADD CONSTRAINT categoria_pregunta 
    FOREIGN KEY (categoria_id) REFERENCES public.categoria (categoria_id);

ALTER TABLE public.escala_valor ADD CONSTRAINT escala_escala_valor 
    FOREIGN KEY (escala_id) REFERENCES public.escala (escala_id);

ALTER TABLE public.asignatura ADD CONSTRAINT carrera_asignatura 
    FOREIGN KEY (carrera_id) REFERENCES public.carrera (carrera_id);

ALTER TABLE public.usuario ADD CONSTRAINT persona_usuario 
    FOREIGN KEY (persona_id) REFERENCES public.persona (persona_id);

ALTER TABLE public.pregunta_sm ADD CONSTRAINT pregunta_pregunta_sm 
    FOREIGN KEY (pregunta_id) REFERENCES public.pregunta (pregunta_id);

ALTER TABLE public.usuario_perm ADD CONSTRAINT rol_usuario_perm 
    FOREIGN KEY (rol_id) REFERENCES public.rol (rol_id);

ALTER TABLE public.usuario_perm ADD CONSTRAINT usuario_usuario_perm 
    FOREIGN KEY (usuario_id) REFERENCES public.usuario (usuario_id);

ALTER TABLE public.carrera ADD CONSTRAINT departamento_carrera 
    FOREIGN KEY (departamento_id) REFERENCES public.departamento (departamento_id);

ALTER TABLE public.encuesta ADD CONSTRAINT escala_encuesta 
    FOREIGN KEY (escala_id) REFERENCES public.escala (escala_id);

ALTER TABLE public.categoria ADD CONSTRAINT encuesta_categoria 
    FOREIGN KEY (encuesta_id) REFERENCES public.encuesta (encuesta_id);

ALTER TABLE public.categoria ADD CONSTRAINT categoria_categoria 
    FOREIGN KEY (categoria_padre) REFERENCES public.categoria (categoria_id);

ALTER TABLE public.docente_dpto ADD CONSTRAINT departamento_docente_dpto 
    FOREIGN KEY (departamento_id) REFERENCES public.departamento (departamento_id);

ALTER TABLE public.docente_dpto ADD CONSTRAINT docente_docente_dpto 
    FOREIGN KEY (docente_id) REFERENCES public.docente (docente_id);

ALTER TABLE public.docente ADD CONSTRAINT persona_docente 
    FOREIGN KEY (persona_id) REFERENCES public.persona (persona_id);

ALTER TABLE public.enc_particular ADD CONSTRAINT encuesta_enc_particular 
    FOREIGN KEY (encuesta_id) REFERENCES public.encuesta (encuesta_id);

ALTER TABLE public.enc_particular ADD CONSTRAINT periodo_lectivo_enc_particular 
    FOREIGN KEY (periodo_lectivo_id) REFERENCES public.periodo_lectivo (periodo_lectivo_id);

ALTER TABLE public.enc_particular ADD CONSTRAINT asignatura_enc_particular 
    FOREIGN KEY (asignatura_id) REFERENCES public.asignatura (asignatura_id);

ALTER TABLE public.enc_particular ADD CONSTRAINT docente_enc_particular 
    FOREIGN KEY (docente_id) REFERENCES public.docente (docente_id);

ALTER TABLE resp_detalle ADD CONSTRAINT respuesta_resp_detalle 
    FOREIGN KEY (respuesta_id) REFERENCES public.respuesta (respuesta_id);

ALTER TABLE resp_detalle ADD CONSTRAINT pregunta_resp_detalle 
    FOREIGN KEY (pregunta_id) REFERENCES public.pregunta (pregunta_id);

ALTER TABLE resp_detalle ADD CONSTRAINT escala_valor_resp_detalle 
    FOREIGN KEY (escala_valor_id) REFERENCES public.escala_valor (escala_valor_id);

ALTER TABLE opcion_elegida ADD CONSTRAINT resp_detalle_opcion_elegida 
    FOREIGN KEY (resp_detalle_id) REFERENCES resp_detalle (resp_detalle_id);

ALTER TABLE opcion_elegida ADD CONSTRAINT pregunta_sm_opcion_elegida 
    FOREIGN KEY (pregunta_sm_id) REFERENCES public.pregunta_sm (pregunta_sm_id);









CREATE SEQUENCE public.resultados_departamento_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE public.resultados_materia_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
CREATE SEQUENCE public.resultados_docente_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

CREATE TABLE public.resultados_departamento (
  resultado int4 DEFAULT nextval('resultados_departamento_seq') NOT NULL,
  enc_particular_id int4 NOT NULL,
  fecha DATE NOT NULL DEFAULT now(),
  texto text,
  promedio decimal(11,2),
  nota varchar(100),
  bold bool NOT NULL,
  part int2 NOT NULL,
  CONSTRAINT pk_resultados_departamento PRIMARY KEY (resultado)
) WITH (OIDS=FALSE);

CREATE TABLE public.resultados_materia (
  resultado int4 DEFAULT nextval('resultados_materia_seq') NOT NULL,
  asignatura_id int4 NOT NULL,
  periodo_lectivo_id int4 NOT NULL,
  fecha DATE NOT NULL DEFAULT now(),
  texto text,
  promedio decimal(11,2),
  nota varchar(100),
  bold bool NOT NULL,
  part int2 NOT NULL,
  CONSTRAINT pk_resultados_materia PRIMARY KEY (resultado)
) WITH (OIDS=FALSE);

CREATE TABLE public.resultados_docente (
  resultado int4 DEFAULT nextval('resultados_docente_seq') NOT NULL,
  enc_particular_id int4 NOT NULL,
  fecha DATE NOT NULL DEFAULT now(),
  texto text,
  promedio decimal(11,2),
  nota varchar(100),
  bold bool NOT NULL,
  part int2 NOT NULL,
  CONSTRAINT pk_resultados_docente PRIMARY KEY (resultado)
) WITH (OIDS=FALSE);

ALTER TABLE public.resultados_departamento ADD CONSTRAINT res_dep_encpartid
FOREIGN KEY (enc_particular_id) REFERENCES public.enc_particular (enc_particular_id);

ALTER TABLE public.resultados_materia ADD CONSTRAINT res_mat_asigid
FOREIGN KEY (asignatura_id) REFERENCES public.asignatura (asignatura_id);

ALTER TABLE public.resultados_materia ADD CONSTRAINT res_mat_perlectid
FOREIGN KEY (periodo_lectivo_id) REFERENCES public.periodo_lectivo (periodo_lectivo_id);

ALTER TABLE public.resultados_docente ADD CONSTRAINT res_doc_encpartid
FOREIGN KEY (enc_particular_id) REFERENCES public.enc_particular (enc_particular_id);



