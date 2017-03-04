create database if not exists microimmo character set utf8 collate utf8_unicode_ci;
use microimmo;

grant all privileges on microimmo.* to 'microimmo_user'@'localhost' identified by 'secret';
