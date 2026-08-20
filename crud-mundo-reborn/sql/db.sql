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

-- ============ Dados de exemplo ============

insert into continentes (nome, populacao, area_km2, total_paises)
values
    ('América do Sul', 434000000, 17840000, 12),
    ('América do Norte', 592000000, 24709000, 23),
    ('Europa', 746000000, 10180000, 50),
    ('Ásia', 4700000000, 44579000, 49),
    ('África', 1400000000, 30370000, 54),
    ('Oceania', 43000000, 8526000, 14);

insert into governantes (nome, partido_politico, data_nascimento, data_inicio_mandato, data_fim_mandato)
values
    ('Luiz Inácio Lula da Silva', 'PT', '1945-10-27', '2023-01-01', null),
    ('Javier Milei', 'La Libertad Avanza', '1970-10-22', '2023-12-10', null),
    ('Emmanuel Macron', 'Renaissance', '1977-12-21', '2017-05-14', null),
    ('Xi Jinping', 'Partido Comunista Chinês', '1953-06-15', '2013-03-14', null),
    ('Cyril Ramaphosa', 'ANC', '1952-11-17', '2018-02-15', null),
    ('Anthony Albanese', 'Partido Trabalhista', '1963-03-02', '2022-05-23', null);

insert into paises (nome, continente_id, populacao, area_km2, idioma, governante_id, clima, regime_politico, moeda)
values
    ('Brasil', 1, 203000000, 8515767, 'Português', 1, 'Tropical', 'República Presidencialista', 'Real (BRL)'),
    ('Argentina', 1, 46000000, 2780400, 'Espanhol', 2, 'Temperado', 'República Presidencialista', 'Peso Argentino (ARS)'),
    ('França', 3, 68000000, 643801, 'Francês', 3, 'Temperado', 'República Semipresidencialista', 'Euro (EUR)'),
    ('China', 4, 1410000000, 9596961, 'Mandarim', 4, 'Variado', 'República Socialista', 'Yuan (CNY)'),
    ('África do Sul', 5, 62000000, 1221037, '11 idiomas oficiais', 5, 'Variado', 'República Parlamentarista', 'Rand (ZAR)'),
    ('Austrália', 6, 26000000, 7692024, 'Inglês', 6, 'Desértico/Temperado', 'Monarquia Constitucional', 'Dólar Australiano (AUD)');

insert into cidades (nome, pais_id, populacao, area_km2, governante_id, clima, data_fundacao)
values
    ('São Paulo', 1, 11450000, 1521, null, 'Subtropical', '1554-01-25'),
    ('Rio de Janeiro', 1, 6211000, 1200, null, 'Tropical', '1565-03-01'),
    ('Buenos Aires', 2, 2890000, 203, null, 'Temperado', '1536-02-02'),
    ('Paris', 3, 2140000, 105, null, 'Temperado', null),
    ('Pequim', 4, 21890000, 16410, null, 'Continental', null),
    ('Cidade do Cabo', 5, 433000, 2445, null, 'Mediterrâneo', '1652-04-06'),
    ('Sydney', 6, 5310000, 12368, null, 'Subtropical', '1788-01-26');