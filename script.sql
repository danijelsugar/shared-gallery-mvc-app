DROP DATABASE IF EXISTS shared_gallery;
CREATE DATABASE shared_gallery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
use shared_gallery;

create table user (
id int not null primary key auto_increment,
firstName varchar(50),
lastName varchar(50),
userName varchar(50) not null,
email varchar(100) not null,
address varchar(100) not null,
password char(60) not null
)engine=InnoDB;

create unique index unique_email on user(email);

create table images (
id int not null primary key auto_increment,
user int not null,
name varchar(100) not null,
imgLocation varchar(100) not null
)engine=InnoDB;

create table token_auth (
id int not null primary key auto_increment,
user int not null,
passwordHash varchar(100) not null,
selectorHash varchar(100) not null,
isExpired boolean not null default 0,
expiryDate timestamp not null default current_timestamp on update current_timestamp
)engine=InnoDB;

alter table images add foreign key (user) references user(id);
