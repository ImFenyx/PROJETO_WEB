create database if not exists bd_mundo;

use bd_mundo;

create table if not exists
    governantes (
        id int unsigned not null auto_increment primary key,
        nome varchar(255) not null,
        partido_politico varchar(100),
        data_nascimento date,
        idade tinyint unsigned as (timestampdiff (year, data_nascimento, curdate())) virtual,
        data_inicio_mandato date,
        data_fim_mandato date
    );

create table if not exists
    continentes (
        id int unsigned not null auto_increment primary key,
        nome varchar(255) not null unique,
        populacao bigint unsigned,
        area_km2 decimal(15, 2),
        total_paises smallint unsigned
    );

create table if not exists
    paises (
        id int unsigned not null auto_increment primary key,
        nome varchar(255) not null,
        continente_id int unsigned,
        populacao bigint unsigned,
        area_km2 decimal(15, 2),
        idioma varchar(100),
        governante_id int unsigned,
        clima varchar(100),
        regime_politico varchar(100),
        moeda varchar(100),
        foreign key (continente_id) references continentes (id),
        foreign key (governante_id) references governantes (id)
    );

create table if not exists
    cidades (
        id int unsigned not null auto_increment primary key,
        nome varchar(255) not null,
        pais_id int unsigned,
        populacao bigint unsigned,
        area_km2 decimal(15, 2),
        governante_id int unsigned,
        clima varchar(100),
        data_fundacao date,
        foreign key (pais_id) references paises (id),
        foreign key (governante_id) references governantes (id)
    );