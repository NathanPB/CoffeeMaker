create table user (
      usr varchar(32) unique,
      pwd varchar(32) not null,
      createdAt datetime default now(),
      createdBy varchar(32),

      primary key (usr),
      foreign key (createdBy) references user(usr)
);

create table ingredient (
    id int auto_increment,
    name varchar(32) unique not null,
    type varchar(1) not null check (type in ('0', '1')),
    primary key (id)
);

create table recipe (
    id int auto_increment,
    name varchar(32) unique not null,
    primary key (id)
);

create table ingredientOnRecipe (
    id int auto_increment,
    recipe int not null,
    ingredient int not null,
    amount int not null check ( amount > 0 ),

    primary key (id),
    foreign key (recipe) references recipe(id) on delete cascade,
    foreign key (ingredient) references ingredient(id)
);

insert into user(usr, pwd) values ('admin', '202cb962ac59075b964b07152d234b70');