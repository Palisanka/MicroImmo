drop table if exists t_user;
drop table if exists t_annonce;

create table t_annonce (
    annonce_id integer not null primary key auto_increment,
    annonce_title varchar(100) not null,
		annonce_prix double(16,2) not null,
		annonce_surface double(6,2) not null,
    annonce_pieces integer not null,
		annonce_dateAjout date not null,
		annonce_dateMaj date,
		annonce_ville varchar(100) not null,
		annonce_quartier varchar(100) not null,
		annonce_url varchar(100) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;

create table t_user (
    usr_id integer not null primary key auto_increment,
    usr_name varchar(50) not null,
    usr_password varchar(88) not null,
    usr_salt varchar(23) not null,
    usr_role varchar(50) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;
