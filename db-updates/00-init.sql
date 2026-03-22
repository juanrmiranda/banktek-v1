-- =========================================================
-- BANTEK - BASE ESTRUCTURAL
-- PostgreSQL 17
-- Schemas:
--   generales
--   clientes
-- =========================================================

-- ---------------------------------------------------------
-- Extensiones recomendadas
-- ---------------------------------------------------------
create extension if not exists pgcrypto;

-- =========================================================
-- SCHEMAS
-- =========================================================
create schema if not exists generales;
create schema if not exists clientes;

-- =========================================================
-- TIPOS ENUM
-- =========================================================

create type clientes.tipo_persona as enum ('NATURAL', 'JURIDICA');

create type clientes.estado_registro as enum (
    'ACTIVO',
    'INACTIVO',
    'BLOQUEADO',
    'ELIMINADO'
);

-- =========================================================
-- TABLAS DE CATALOGO - GENERALES
-- =========================================================

-- ---------------------------------------------------------
-- Usuarios
-- ---------------------------------------------------------

CREATE TABLE generales.usuario
(
  correlativo serial primary key,
uuid  uuid not null default gen_random_uuid(),
  codigo_usuario character varying(20),
  nombre character varying(40),
  nombres character varying(60),
  apellidos character varying(60),
  clave text,
  fecha_creacion timestamp with time zone NOT NULL DEFAULT now(),
  creado_por character varying(20),
  activo boolean NOT NULL DEFAULT true,
  actualizado_por character varying(20),
  fecha_actualizado timestamp without time zone,
  eliminado_por character varying(20),
  fecha_eliminado timestamp without time zone,
  eliminado boolean DEFAULT false,
  agencia integer NOT NULL,
  dui character varying(10),
  reinicio_clave boolean NOT NULL DEFAULT true,
  CONSTRAINT "Ya existe un usuario con este DUI" UNIQUE (dui),
  CONSTRAINT gen_usuarios_codigo_usuario_key UNIQUE (codigo_usuario)
);

-- ---------------------------------------------------------
-- País
-- ---------------------------------------------------------
create table if not exists generales.pais (
    id_pais                  serial primary key,
    codigo              varchar(4) not null,
    codigo_iso2              varchar(2) not null,
    nombre                   varchar(100) not null,
    nacionalidad             varchar(100) null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null   references generales.usuario(codigo_usuario),
    fecha_modificacion       timestamp null,
    constraint uq_pais_iso2 unique (codigo_iso2),
    constraint uq_pais_codigo unique (codigo),
    constraint uq_pais_nombre unique (nombre)
);

comment on table generales.pais is 'Catálogo maestro de países.';
comment on column generales.pais.nacionalidad is 'Gentilicio o nacionalidad asociada al país.';

-- ---------------------------------------------------------
-- Departamento / Estado / Provincia
-- ---------------------------------------------------------
create table if not exists generales.departamento (
    id_departamento          serial primary key,
    id_pais                  smallint not null references generales.pais(id_pais),
    codigo                   varchar(10) not null,
    nombre                   varchar(100) not null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_departamento_pais_codigo unique (id_pais, codigo),
    constraint uq_departamento_pais_nombre unique (id_pais, nombre)
);

comment on table generales.departamento is 'Catálogo de departamentos/provincias por país.';

-- ---------------------------------------------------------
-- Municipio / Ciudad
-- ---------------------------------------------------------
create table if not exists generales.municipio (
    id_municipio             serial primary key,
    id_departamento          integer not null references generales.departamento(id_departamento),
    codigo                   varchar(10) not null,
    nombre                   varchar(100) not null,
    distrito                 varchar(100) not null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_municipio_departamento_codigo unique (id_departamento, codigo),
    constraint uq_municipio_departamento_nombre unique (id_departamento, nombre)
);

comment on table generales.municipio is 'Catálogo de municipios/ciudades por departamento.';

-- ---------------------------------------------------------
-- Sexo
-- ---------------------------------------------------------
create table if not exists generales.sexo (
    id_sexo                  serial primary key,
    codigo                   varchar(10) not null,
    nombre                   varchar(50) not null,
    descripcion              varchar(150) null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_sexo_codigo unique (codigo),
    constraint uq_sexo_nombre unique (nombre)
);

comment on table generales.sexo is 'Catálogo de sexo/género conforme a la política institucional.';
comment on column generales.sexo.codigo is 'Ejemplo: M, F, N/E.';

-- ---------------------------------------------------------
-- Estado civil
-- ---------------------------------------------------------
create table if not exists generales.estado_civil (
    id_estado_civil          serial primary key,
    codigo                   varchar(10) not null,
    nombre                   varchar(50) not null,
    descripcion              varchar(150) null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_estado_civil_codigo unique (codigo),
    constraint uq_estado_civil_nombre unique (nombre)
);

comment on table generales.estado_civil is 'Catálogo de estados civiles.';

-- ---------------------------------------------------------
-- Tipo de documento
-- ---------------------------------------------------------
create table if not exists generales.tipo_documento (
    id_tipo_documento        serial primary key,
    codigo                   varchar(20) not null,
    nombre                   varchar(100) not null,
    descripcion              varchar(200) null,
    aplica_natural           boolean not null default true,
    aplica_juridica          boolean not null default false,
    requiere_vencimiento     boolean not null default false,
    requiere_pais_emision    boolean not null default false,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root' references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_tipo_documento_codigo unique (codigo),
    constraint uq_tipo_documento_nombre unique (nombre)
);

comment on table generales.tipo_documento is 'Catálogo de tipos documentales: DUI, NIT, Pasaporte, Escritura, etc.';

-- ---------------------------------------------------------
-- Tipo de dirección
-- ---------------------------------------------------------
create table if not exists generales.tipo_direccion (
    id_tipo_direccion        serial primary key,
    codigo                   varchar(20) not null,
    nombre                   varchar(100) not null,
    descripcion              varchar(200) null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root' references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_tipo_direccion_codigo unique (codigo),
    constraint uq_tipo_direccion_nombre unique (nombre)
);

comment on table generales.tipo_direccion is 'Catálogo para dirección residencial, laboral, fiscal, notificación, etc.';

-- ---------------------------------------------------------
-- Tipo de teléfono
-- ---------------------------------------------------------
create table if not exists generales.tipo_telefono (
    id_tipo_telefono         serial primary key,
    codigo                   varchar(20) not null,
    nombre                   varchar(100) not null,
    descripcion              varchar(200) null,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root'  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_tipo_telefono_codigo unique (codigo),
    constraint uq_tipo_telefono_nombre unique (nombre)
);

comment on table generales.tipo_telefono is 'Catálogo para móvil, residencial, laboral, contacto alterno, etc.';

-- ---------------------------------------------------------
-- Tipo de relación general entre clientes
-- ---------------------------------------------------------
create table if not exists generales.tipo_relacion (
    id_tipo_relacion         serial primary key,
    codigo                   varchar(30) not null,
    nombre                   varchar(100) not null,
    descripcion              varchar(200) null,
    bidireccional            boolean not null default false,
    activo                   boolean not null default true,
    orden_visual             integer not null default 0,
    creado_por               varchar(50) not null default 'root' references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_tipo_relacion_codigo unique (codigo),
    constraint uq_tipo_relacion_nombre unique (nombre)
);

comment on table generales.tipo_relacion is 'Relaciones generales entre clientes: cónyuge, tutor, representante legal, etc.';

-- =========================================================
-- TABLAS MAESTRAS - CLIENTES
-- =========================================================

-- ---------------------------------------------------------
-- Cliente maestro
-- ---------------------------------------------------------
create table if not exists clientes.cliente (
    id_cliente               serial primary key,
    uuid                     uuid not null default gen_random_uuid(),
    tipo_persona             clientes.tipo_persona not null,
    codigo_cliente           varchar(30) not null,
    estado_registro          clientes.estado_registro not null default 'ACTIVO',
    fecha_alta               timestamp not null default current_timestamp,
    fecha_baja               timestamp null,
    observaciones            text null,
    creado_por               varchar(50) not null references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint uq_cliente_uuid unique (uuid),
    constraint uq_cliente_codigo unique (codigo_cliente),
    constraint chk_cliente_fecha_baja check (
        fecha_baja is null or fecha_baja >= fecha_alta
    )
);

comment on table clientes.cliente is 'Tabla maestra de terceros del sistema. Contiene personas naturales y jurídicas.';
comment on column clientes.cliente.codigo_cliente is 'Identificador funcional interno del cliente.';
comment on column clientes.cliente.tipo_persona is 'Define si el cliente es NATURAL o JURIDICA.';

-- ---------------------------------------------------------
-- Cliente natural
-- ---------------------------------------------------------
create table if not exists clientes.cliente_natural (
    id_cliente               bigint primary key
                             references clientes.cliente(id_cliente) on delete restrict,
    primer_nombre            varchar(50) not null,
    segundo_nombre           varchar(50) null,
    tercer_nombre            varchar(50) null,
    primer_apellido          varchar(50) not null,
    segundo_apellido         varchar(50) null,
    apellido_casada          varchar(50) null,
    nombre_completo_legal    varchar(300) not null,
    fecha_nacimiento         date null,
    id_sexo                  smallint null
                             references generales.sexo(id_sexo),
    id_estado_civil          smallint null
                             references generales.estado_civil(id_estado_civil),
    id_pais_nacionalidad     smallint null
                             references generales.pais(id_pais),
    lugar_nacimiento         varchar(150) null,
    profesion_oficio         varchar(120) null,
    creado_por               varchar(50) not null  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_natural_fecha_nacimiento check (
        fecha_nacimiento is null or fecha_nacimiento <= current_date
    )
);

comment on table clientes.cliente_natural is 'Extensión de datos para clientes persona natural.';
comment on column clientes.cliente_natural.nombre_completo_legal is 'Nombre completo legal congelado para uso operativo y documental.';

-- ---------------------------------------------------------
-- Cliente jurídica
-- ---------------------------------------------------------
create table if not exists clientes.cliente_juridica (
    id_cliente                    bigint primary key
                                  references clientes.cliente(id_cliente) on delete restrict,
    razon_social                  varchar(250) not null,
    nombre_comercial              varchar(250) null,
    sigla                         varchar(50) null,
    fecha_constitucion            date null,
    id_pais_constitucion          smallint null
                                  references generales.pais(id_pais),
    giro                          varchar(150) null,
    representante_legal_texto     varchar(250) null,
    creado_por               varchar(50) not null  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion                timestamp not null default current_timestamp,
    modificado_por                varchar(50) null,
    fecha_modificacion            timestamp null,
    constraint chk_cliente_juridica_fecha_constitucion check (
        fecha_constitucion is null or fecha_constitucion <= current_date
    )
);

comment on table clientes.cliente_juridica is 'Extensión de datos para clientes persona jurídica.';

-- ---------------------------------------------------------
-- Documento de cliente
-- ---------------------------------------------------------
create table if not exists clientes.cliente_documento (
    id_cliente_documento      bigint generated always as identity primary key,
    id_cliente               bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    id_tipo_documento        smallint not null
                             references generales.tipo_documento(id_tipo_documento),
    numero_documento         varchar(50) not null,
    fecha_emision            date null,
    fecha_vencimiento        date null,
    id_pais_emision          smallint null
                             references generales.pais(id_pais),
    es_principal             boolean not null default false,
    activo                   boolean not null default true,
    observaciones            text null,
    creado_por               varchar(50) not null references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_documento_numero_no_vacio check (btrim(numero_documento) <> ''),
    constraint chk_cliente_documento_vigencia check (
        fecha_vencimiento is null
        or fecha_emision is null
        or fecha_vencimiento >= fecha_emision
    )
);

comment on table clientes.cliente_documento is 'Documentos de identificación o constitución asociados al cliente.';
comment on column clientes.cliente_documento.es_principal is 'Indica el documento principal del cliente dentro del conjunto activo.';

-- ---------------------------------------------------------
-- Dirección de cliente
-- ---------------------------------------------------------
create table if not exists clientes.cliente_direccion (
    id_cliente_direccion      bigint generated always as identity primary key,
    id_cliente               bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    id_tipo_direccion        smallint not null
                             references generales.tipo_direccion(id_tipo_direccion),
    linea_1                  varchar(200) not null,
    linea_2                  varchar(200) null,
    referencia               varchar(250) null,
    id_pais                  smallint not null
                             references generales.pais(id_pais),
    id_departamento          integer null
                             references generales.departamento(id_departamento),
    id_municipio             integer null
                             references generales.municipio(id_municipio),
    codigo_postal            varchar(20) null,
    es_principal             boolean not null default false,
    activo                   boolean not null default true,
    creado_por               varchar(50) not null  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_direccion_linea_1_no_vacia check (btrim(linea_1) <> '')
);

comment on table clientes.cliente_direccion is 'Direcciones asociadas al cliente.';
comment on column clientes.cliente_direccion.linea_1 is 'Primer nivel estructurado de dirección.';
comment on column clientes.cliente_direccion.referencia is 'Texto libre adicional para facilitar ubicación física.';

-- ---------------------------------------------------------
-- Teléfono de cliente
-- ---------------------------------------------------------
create table if not exists clientes.cliente_telefono (
    id_cliente_telefono       bigint generated always as identity primary key,
    id_cliente               bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    id_tipo_telefono         smallint not null
                             references generales.tipo_telefono(id_tipo_telefono),
    numero_telefono          varchar(30) not null,
    es_principal             boolean not null default false,
    activo                   boolean not null default true,
    observaciones            varchar(150) null,
    creado_por               varchar(50) not null references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_telefono_numero_no_vacio check (btrim(numero_telefono) <> '')
);

comment on table clientes.cliente_telefono is 'Teléfonos asociados al cliente.';

-- ---------------------------------------------------------
-- Correo de cliente
-- ---------------------------------------------------------
create table if not exists clientes.cliente_correo (
    id_cliente_correo         bigint generated always as identity primary key,
    id_cliente               bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    correo_electronico       varchar(254) not null,
    es_principal             boolean not null default false,
    activo                   boolean not null default true,
    creado_por               varchar(50) not null  references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_correo_no_vacio check (btrim(correo_electronico) <> ''),
    constraint chk_cliente_correo_formato_basico check (
        correo_electronico ~* '^[A-Z0-9._%+\-]+@[A-Z0-9.\-]+\.[A-Z]{2,}$'
    )
);

comment on table clientes.cliente_correo is 'Correos electrónicos asociados al cliente.';

-- ---------------------------------------------------------
-- Relación general entre clientes
-- ---------------------------------------------------------
create table if not exists clientes.cliente_relacion (
    id_cliente_relacion       bigint generated always as identity primary key,
    id_cliente_origen        bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    id_cliente_relacionado   bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    id_tipo_relacion         smallint not null
                             references generales.tipo_relacion(id_tipo_relacion),
    fecha_inicio             date not null default current_date,
    fecha_fin                date null,
    observaciones            varchar(250) null,
    activo                   boolean not null default true,
    creado_por               varchar(50) not null references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_relacion_distinto check (
        id_cliente_origen <> id_cliente_relacionado
    ),
    constraint chk_cliente_relacion_fechas check (
        fecha_fin is null or fecha_fin >= fecha_inicio
    )
);

comment on table clientes.cliente_relacion is 'Relaciones generales entre clientes. No debe usarse para relaciones dependientes de una operación específica.';
comment on column clientes.cliente_relacion.id_cliente_origen is 'Cliente principal desde cuya perspectiva se registra la relación.';
comment on column clientes.cliente_relacion.id_cliente_relacionado is 'Cliente relacionado con el cliente origen.';

-- ---------------------------------------------------------
-- Actividad económica básica
-- ---------------------------------------------------------
create table if not exists clientes.cliente_actividad_economica (
    id_cliente_actividad      bigint generated always as identity primary key,
    id_cliente               bigint not null
                             references clientes.cliente(id_cliente) on delete restrict,
    ocupacion                varchar(120) null,
    nombre_empleador         varchar(200) null,
    direccion_empleador      varchar(250) null,
    cargo                    varchar(120) null,
    ingreso_mensual          numeric(14,2) null,
    fecha_inicio             date null,
    activo                   boolean not null default true,
    creado_por               varchar(50) not null references generales.usuario(codigo_usuario) on update restrict on delete restrict,
    fecha_creacion           timestamp not null default current_timestamp,
    modificado_por           varchar(50) null,
    fecha_modificacion       timestamp null,
    constraint chk_cliente_actividad_ingreso_no_negativo check (
        ingreso_mensual is null or ingreso_mensual >= 0
    ),
    constraint chk_cliente_actividad_fecha_inicio check (
        fecha_inicio is null or fecha_inicio <= current_date
    )
);

comment on table clientes.cliente_actividad_economica is 'Perfil básico económico/laboral del cliente para evaluación y conocimiento del cliente.';

-- =========================================================
-- INDICES
-- =========================================================

-- Generales
create index if not exists ix_departamento_id_pais
    on generales.departamento(id_pais);

create index if not exists ix_municipio_id_departamento
    on generales.municipio(id_departamento);

create index if not exists ix_tipo_documento_aplica
    on generales.tipo_documento(aplica_natural, aplica_juridica);

-- Clientes
create index if not exists ix_cliente_tipo_persona
    on clientes.cliente(tipo_persona);

create index if not exists ix_cliente_estado_registro
    on clientes.cliente(estado_registro);

create index if not exists ix_cliente_fecha_alta
    on clientes.cliente(fecha_alta);

create index if not exists ix_cliente_natural_nombre
    on clientes.cliente_natural(primer_nombre, primer_apellido, segundo_apellido);

create index if not exists ix_cliente_natural_nombre_completo_legal
    on clientes.cliente_natural(nombre_completo_legal);

create index if not exists ix_cliente_juridica_razon_social
    on clientes.cliente_juridica(razon_social);

create index if not exists ix_cliente_juridica_nombre_comercial
    on clientes.cliente_juridica(nombre_comercial);

create index if not exists ix_cliente_documento_id_cliente
    on clientes.cliente_documento(id_cliente);

create index if not exists ix_cliente_documento_numero
    on clientes.cliente_documento(numero_documento);

create index if not exists ix_cliente_documento_tipo_numero
    on clientes.cliente_documento(id_tipo_documento, numero_documento);

create index if not exists ix_cliente_direccion_id_cliente
    on clientes.cliente_direccion(id_cliente);

create index if not exists ix_cliente_direccion_ubicacion
    on clientes.cliente_direccion(id_pais, id_departamento, id_municipio);

create index if not exists ix_cliente_telefono_id_cliente
    on clientes.cliente_telefono(id_cliente);

create index if not exists ix_cliente_telefono_numero
    on clientes.cliente_telefono(numero_telefono);

create index if not exists ix_cliente_correo_id_cliente
    on clientes.cliente_correo(id_cliente);

create index if not exists ix_cliente_correo_correo
    on clientes.cliente_correo(correo_electronico);

create index if not exists ix_cliente_relacion_origen
    on clientes.cliente_relacion(id_cliente_origen);

create index if not exists ix_cliente_relacion_relacionado
    on clientes.cliente_relacion(id_cliente_relacionado);

create index if not exists ix_cliente_relacion_tipo
    on clientes.cliente_relacion(id_tipo_relacion);

create index if not exists ix_cliente_actividad_id_cliente
    on clientes.cliente_actividad_economica(id_cliente);

-- =========================================================
-- UNIQUE PARCIALES / REGLAS DE NEGOCIO
-- =========================================================

-- Solo un documento principal activo por cliente
create unique index if not exists ux_cliente_documento_principal_activo
    on clientes.cliente_documento(id_cliente)
    where es_principal = true and activo = true;

-- Evita duplicar el mismo documento activo por tipo y número
create unique index if not exists ux_cliente_documento_tipo_numero_activo
    on clientes.cliente_documento(id_tipo_documento, numero_documento)
    where activo = true;

-- Solo una dirección principal activa por cliente
create unique index if not exists ux_cliente_direccion_principal_activa
    on clientes.cliente_direccion(id_cliente)
    where es_principal = true and activo = true;

-- Solo un teléfono principal activo por cliente
create unique index if not exists ux_cliente_telefono_principal_activo
    on clientes.cliente_telefono(id_cliente)
    where es_principal = true and activo = true;

-- Solo un correo principal activo por cliente
create unique index if not exists ux_cliente_correo_principal_activo
    on clientes.cliente_correo(id_cliente)
    where es_principal = true and activo = true;

-- Evita relaciones generales duplicadas activas
create unique index if not exists ux_cliente_relacion_activa
    on clientes.cliente_relacion(id_cliente_origen, id_cliente_relacionado, id_tipo_relacion)
    where activo = true;

-- =========================================================
-- TRIGGERS DE VALIDACION DE SUBTIPO
-- =========================================================

create or replace function clientes.fn_validar_subtipo_cliente_natural()
returns trigger
language plpgsql
as $$
declare
    v_tipo clientes.tipo_persona;
begin
    select tipo_persona
      into v_tipo
      from clientes.cliente
     where id_cliente = new.id_cliente;

    if v_tipo is distinct from 'NATURAL' then
        raise exception 'El cliente % no es de tipo NATURAL', new.id_cliente;
    end if;

    return new;
end;
$$;

create or replace function clientes.fn_validar_subtipo_cliente_juridica()
returns trigger
language plpgsql
as $$
declare
    v_tipo clientes.tipo_persona;
begin
    select tipo_persona
      into v_tipo
      from clientes.cliente
     where id_cliente = new.id_cliente;

    if v_tipo is distinct from 'JURIDICA' then
        raise exception 'El cliente % no es de tipo JURIDICA', new.id_cliente;
    end if;

    return new;
end;
$$;

create or replace trigger trg_validar_subtipo_cliente_natural
before insert or update on clientes.cliente_natural
for each row
execute function clientes.fn_validar_subtipo_cliente_natural();

create or replace trigger trg_validar_subtipo_cliente_juridica
before insert or update on clientes.cliente_juridica
for each row
execute function clientes.fn_validar_subtipo_cliente_juridica();

-- =========================================================
-- DATOS MINIMOS DE CATALOGO
-- =========================================================

-- Países básicos
insert into generales.pais (codigo_iso2, codigo, nombre, nacionalidad, orden_visual)
values
    ('SV', 'SLV', 'EL SALVADOR', 'SALVADOREÑA', 1),
    ('GT', '9483', 'GUATEMALA', 'GUATEMALTECA', 2)
on conflict do nothing;

-- Sexo
insert into generales.sexo (codigo, nombre, descripcion, orden_visual)
values
    ('M', 'MASCULINO', 'Sexo masculino', 1),
    ('F', 'FEMENINO', 'Sexo femenino', 2),
    ('0', 'NO ESPECIFICADO', 'No especificado o no informado', 3)
on conflict do nothing;

-- Estado civil
insert into generales.estado_civil (codigo, nombre, orden_visual)
values
    ('SOL', 'SOLTERO/A', 1),
    ('CAS', 'CASADO/A', 2),
    ('DIV', 'DIVORCIADO/A', 3),
    ('VIU', 'VIUDO/A', 4),
    ('UNI', 'UNIÓN LIBRE', 5),
    ('NE', 'NO ESPECIFICADO', 6)
on conflict do nothing;

-- Tipo documento
insert into generales.tipo_documento (
    codigo, nombre, aplica_natural, aplica_juridica, requiere_vencimiento, requiere_pais_emision, orden_visual
)
values
    ('DUI', 'DOCUMENTO ÚNICO DE IDENTIDAD', true, false, true, false, 1),
    ('NIT', 'NÚMERO DE IDENTIFICACIÓN TRIBUTARIA', true, true, false, false, 2),
    ('PAS', 'PASAPORTE', true, false, true, true, 3),
    ('NAC', 'PARTIDA DE NACIMIENTO', true, false, false, false, 4),
    ('ESC', 'ESCRITURA DE CONSTITUCIÓN', false, true, false, true, 5),
    ('REG', 'REGISTRO TRIBUTARIO/LEGAL', false, true, false, true, 6)
on conflict do nothing;

-- Tipo dirección
insert into generales.tipo_direccion (codigo, nombre, orden_visual)
values
    ('RES', 'RESIDENCIAL', 1),
    ('LAB', 'LABORAL', 2),
    ('FIS', 'FISCAL', 3),
    ('NOT', 'NOTIFICACIÓN', 4),
    ('OTR', 'OTRA', 5)
on conflict do nothing;

-- Tipo teléfono
insert into generales.tipo_telefono (codigo, nombre, orden_visual)
values
    ('MOV', 'MÓVIL', 1),
    ('RES', 'RESIDENCIAL', 2),
    ('LAB', 'LABORAL', 3),
    ('ALT', 'ALTERNO', 4),
    ('OTR', 'OTRO', 5)
on conflict do nothing;

-- Tipo relación general
insert into generales.tipo_relacion (codigo, nombre, bidireccional, orden_visual)
values
    ('CONYUGE', 'CÓNYUGE', true, 1),
    ('PADRE', 'PADRE', false, 2),
    ('MADRE', 'MADRE', false, 3),
    ('TUTOR', 'TUTOR', false, 4),
    ('REP_LEGAL', 'REPRESENTANTE LEGAL', false, 5),
    ('APODERADO', 'APODERADO', false, 6),
    ('REF_GENERAL', 'REFERENCIA GENERAL', false, 7),
    ('CONTACTO_ALT', 'CONTACTO ALTERNO', false, 8)
on conflict do nothing;



CREATE OR REPLACE FUNCTION generales.tri_insert_nuevo_usuario()
  RETURNS trigger AS
$BODY$
declare
  _codigo text;
  _nombres text;
  _apellidos text;
  _cant integer;
begin
    _nombres := upper(coalesce(new.nombres, ''));
    _apellidos := upper(coalesce(new.apellidos, ''));

    _nombres := replace(_nombres, 'Ñ', 'N');
    _apellidos := replace(_apellidos, 'Ñ', 'N');

    _codigo := left(
        lower(left(_nombres, 1) || substring(replace(_apellidos, ' ', '') from 1 for 9)) || '000000000',
        10
    );

    select count(*)
      into _cant
      from generales.usuario
     where codigo_usuario = _codigo;

    if _cant > 0 then
        select count(*)
          into _cant
          from generales.usuario
         where left(codigo_usuario, 9) = left(_codigo, 9);

        if _cant > 0 then
            _codigo := left(_codigo, 9) || _cant::text;
        end if;
    end if;
        
	new.codigo_usuario=_codigo;
	
	new.clave=crypt(_codigo, gen_salt('bf'));
	return new;

end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;


CREATE TRIGGER insert_nuevo_usuario
  BEFORE INSERT
  ON generales.usuario
  FOR EACH ROW
  EXECUTE PROCEDURE generales.tri_insert_nuevo_usuario();

update generales.usuario u 
set clave = crypt(codigo_usuario, gen_salt('bf'));



create or replace view generales.usuario_listado as
select
    u.correlativo,
    u.uuid,
    u.codigo_usuario,
    u.nombre,
    u.nombres,
    u.apellidos,
    u.dui,
    u.agencia,
    u.activo,
    u.eliminado,
    u.creado_por,
    u.fecha_creacion,
    to_char(u.fecha_creacion, 'DD/MM/YYYY HH24:MI:SS') as fecha_creacion_descripcion,
    case
        when u.eliminado = true then 'ELIMINADO'
        when u.activo = true then 'ACTIVO'
        else 'INACTIVO'
    end as estado_descripcion,
    (coalesce(u.nombres, '') || ' ' || coalesce(u.apellidos, '')) as nombre_completo
from generales.usuario u
where coalesce(u.eliminado, false) = false;


CREATE TABLE generales.roles
(
  correlativo serial NOT null primary key,
  descripcion character varying(50),
  correlativo_modulos_opciones integer[],
  nivel_acceso integer, -- nivel de acceso para seguridad...
  activo boolean NOT NULL DEFAULT true
);


		INSERT INTO generales.roles(correlativo, descripcion, correlativo_modulos_opciones, nivel_acceso) VALUES (0, 'ROOT', '{9999}',999);

CREATE TABLE generales.usuarios_roles
(
  correlativo serial NOT NULL,
  correlativo_usuario integer,
  correlativo_roles integer NOT NULL DEFAULT 0,
  CONSTRAINT usuarios_roles_pkey PRIMARY KEY (correlativo),
  CONSTRAINT "Posee roles de acceso" FOREIGN KEY (correlativo_usuario)
      REFERENCES generales.usuario (correlativo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT usuarios_roles_correlativo_roles_fkey FOREIGN KEY (correlativo_roles)
      REFERENCES generales.roles (correlativo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT
);


		INSERT INTO generales.usuarios_roles( correlativo_usuario, correlativo_roles) VALUES ( 1, 0);


CREATE OR REPLACE VIEW generales.usuarios_roles_view AS 
 SELECT a.correlativo,
    a.correlativo_usuario,
    a.correlativo_roles,
    b.descripcion,
    b.nivel_acceso,
    c.codigo_usuario
   FROM generales.usuarios_roles a,
    generales.roles b,
    generales.usuario c
  WHERE a.correlativo_roles = b.correlativo AND a.correlativo_usuario = c.correlativo;



CREATE TABLE generales.modulos
(
  correlativo serial NOT NULL,
  descripcion character varying(50),
  controller character varying(20),
  icono character varying(40) NOT NULL DEFAULT 'fas fa-database'::character varying,
  activo boolean NOT NULL DEFAULT true,
  CONSTRAINT modulos_pkey PRIMARY KEY (correlativo)
);


INSERT INTO generales.modulos(
            descripcion, controller, icono)
    VALUES ('Clientes', 'Clientes', 'fas fa-users');



CREATE TABLE generales.modulos_opciones
(
  correlativo serial NOT NULL,
  correlativo_modulo integer NOT NULL,
  descripcion character varying(30),
  function character varying(30),
  activo boolean NOT NULL DEFAULT true,
  CONSTRAINT modulos_opciones_pkey PRIMARY KEY (correlativo),
  CONSTRAINT modulos_opciones_correlativo_modulo_fkey FOREIGN KEY (correlativo_modulo)
      REFERENCES generales.modulos (correlativo) MATCH SIMPLE
      ON UPDATE RESTRICT ON DELETE RESTRICT,
  CONSTRAINT modulos_opciones_correlativo_modulo_function_key UNIQUE (correlativo_modulo, function)
);

INSERT INTO generales.modulos_opciones(
            correlativo_modulo, descripcion, function)
    VALUES (1, 'Listado', 'listado');

INSERT INTO generales.modulos_opciones(
            correlativo_modulo, descripcion, function)
    VALUES (1, 'Nuevo', 'nuevo');

    INSERT INTO generales.modulos_opciones(
            correlativo_modulo, descripcion, function)
    VALUES (1, 'Consulta', 'query');

CREATE OR REPLACE VIEW generales.view_menu_sistema AS 
 SELECT b.correlativo AS correlativo_modulo,
    b.descripcion AS descripcion_modulo,
    b.controller,
    b.icono,
    a.correlativo AS correlativo_opcion,
    a.descripcion AS descripcion_opcion,
    a.function,
    (b.controller::text || '::'::text) || a.function::text AS method,
    (b.controller::text || '/'::text) || a.function::text AS go_to
   FROM generales.modulos_opciones a
     RIGHT JOIN generales.modulos b ON a.correlativo_modulo = b.correlativo
  WHERE b.activo = true AND a.activo = true
  ORDER BY b.correlativo, a.correlativo;



CREATE OR REPLACE VIEW generales.auth_usuarios_modulos_opciones AS 
 SELECT b.correlativo_usuario,
    b.correlativo_roles,
    unnest(a.correlativo_modulos_opciones) AS correlativo_opcion
   FROM generales.roles a,
    generales.usuarios_roles b
  WHERE a.correlativo = b.correlativo_roles;



		INSERT INTO generales.roles(descripcion, correlativo_modulos_opciones, nivel_acceso) VALUES ( 'EJECUTIVO NEGOCIOS', '{1,2,3}',10);


update generales.usuarios_roles set correlativo_roles=1;


ALTER TABLE clientes.cliente RENAME COLUMN cliente TO correlativo;

ALTER TABLE clientes.cliente_natural  RENAME COLUMN id_cliente TO correlativo_cliente;



create or replace view clientes.clientes_listado as
select
   *,
    to_char(fecha_creacion, 'DD/MM/YYYY HH24:MI:SS') as fecha_creacion_descripcion,
    estado_registro estado_descripcion
from clientes.cliente;