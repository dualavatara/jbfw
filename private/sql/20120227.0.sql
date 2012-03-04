CREATE TABLE `jbfw`.`admin_users` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `login` TINYTEXT  NOT NULL,
  `password` TINYTEXT  NOT NULL,
  `name` TINYTEXT  NOT NULL,
  `created` DATETIME  NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = MyISAM;

CREATE TABLE `jbfw`.`admin_access` (
  `id` INTEGER  NOT NULL AUTO_INCREMENT,
  `user_id` INTEGER  NOT NULL,
  `route_name` TINYTEXT  NOT NULL,
  PRIMARY KEY (`id`)
)
ENGINE = MyISAM;

insert into admin_users(id, login, password, created) VALUES(1, "admin", MD5("admin"), NOW());
insert into admin_access(user_id, route_name) VALUES(1, "home");
insert into admin_access(user_id, route_name) VALUES(1, "user_edit");
insert into admin_access(user_id, route_name) VALUES(1, "user_delete");
insert into admin_access(user_id, route_name) VALUES(1, "user_list");
insert into admin_access(user_id, route_name) VALUES(1, "user_save");
insert into admin_access(user_id, route_name) VALUES(1, "user_add");