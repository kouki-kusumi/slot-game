create table users (
  id int not null auto_increment primary key,
  name varchar(32) unique not null,
  password varchar(255) not null,
  coin int,
  created datetime,
  modified datetime,
  index name_index(name)
);