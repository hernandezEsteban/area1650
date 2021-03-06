create table if not exists country (
     code smallint primary key, 
     name varchar(125) charset utf8
);

create table if not exists tournament (
     code smallint, 
	 country smallint,
	 unique(code, country)
);

create table if not exists group_stage(
     id smallint primary key,
     tournament smallint,
	 group_code varchar(1),
	 squad smallint
);

create index if not exists group_stage_i on group_stage (tournament, group_code);

create table if not exists time_type (
     id smallint primary key,
     name varchar(25) charset utf8
);

create table if not exists game_type (
    id smallint primary key,
    name varchar(25) charset utf8
);

create table if not exists game (
     matchid smallint primary key,
     matchdate date,
	 game_type smallint,
	 country smallint
);

alter table game add column country smallint;

create table if not exists game_score (
     id smallint, 
     matchid smallint,
     squad smallint,
     goals tinyint,
     points tinyint,
     time_type smallint,
     unique(id, matchid)
);

create index if not exists game_score_i on game_score (squad);