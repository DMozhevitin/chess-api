create table if not exists `game` (
    `id` int(11) not null auto_increment,
    `is_white_turn` bit(1) not null,
    `is_check` bit(1) not null,
    `is_active` bit(1) not null,
    `white_king_x` int(11) not null,
    `white_king_y` int(11) not null,
    `black_king_x` int(11) not null,
    `black_king_y` int(11) not null,
    primary key(`id`)
) engine=InnoDB default charset=utf8;

create table if not exists `piece` (
`id` int(11) auto_increment primary key,
`x` int(11) not null,
`y` int(11) not null,
`color` int(1) not null,
`symbol` varchar(1) not null
) engine=innodb;