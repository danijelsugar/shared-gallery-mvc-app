DROP DATABASE IF EXISTS shared_gallery;
CREATE DATABASE shared_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use shared_gallery;

create table user (
id int not null primary key auto_increment,
firstname varchar(50),
lastname varchar(50),
username varchar(50) not null,
email varchar(100) not null,
password char(60) not null
)engine=InnoDB;

create unique index unique_email on user(email);

create table images (
id int not null primary key auto_increment,
user int not null,
name varchar(100) not null
)engine=InnoDB;

alter table images add foreign key (user) references user(id);