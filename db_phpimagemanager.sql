array(
'insert into olog_auth_permission (title) values ("PERMISSION_PHPIMAGEMANAGER_MANAGE_IMAGES") /* rand2652403456 */;',
'create table olog_image_image (id int not null auto_increment primary key, created_at_ts int not null default 0) engine InnoDB default charset utf8 /* rand8606 */;',
'alter table olog_image_image add column storage_name varchar(255)  not null    /* rand903603 */;',
'alter table olog_image_image add column file_path_in_storage varchar(255)  not null    /* rand465839 */;',
'alter table olog_image_image add column title varchar(1000)  not null   default ""  /* rand499882 */;',
)
