create table users (       
    user_id bigint unsigned not null auto_increment,
    session_id bigint unsigned not null,
    auth_type varchar(30) not null,
    name varchar(500) not null,
    created timestamp not null default current_timestamp,
    primary key (user_id),
    index (name),
    index (session_id),
    index (created)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

create table categories (
    user_id bigint unsigned not null,
    categories_json text not null, # ぶっちゃけ面倒なのでjsonで持つ
    type varchar(10) not null, # spending / income / fixed_spending
    primary key (user_id),
    index (type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

create table spending (
    spend_id bigint unsigned not null auto_increment,
    user_id bigint unsigned not null,
    type varchar(10) not null, # 私費か経費か
    category varchar(100),
    amount bigint not null,
    memo varchar(1000),
    created timestamp not null default current_timestamp,
    delete_flag enum('Y', 'N') not null default 'N',
    primary key (spend_id),
    index (user_id),
    index (category),
    index (delete_flag),
    index (created)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

create table fixed_spending (
    fixed_id bigint unsigned not null auto_increment,
    user_id bigint unsigned not null,
    type varchar(10) not null, # 私費か経費か
    category varchar(100),
    amount bigint not null,
    memo varchar(1000),
    frequency varchar(10) not null, # daily / monthly / yearly
    created timestamp not null default current_timestamp,
    delete_flag enum('Y', 'N') not null default 'N',
    primary key (fixed_id),
    index (user_id),
    index (category),
    index (delete_flag),
    index (created)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

create table income (
    income_id bigint unsigned not null auto_increment,
    user_id bigint unsigned not null,
    category varchar(100),
    amount bigint not null,
    memo varchar(1000),
    created timestamp not null default current_timestamp,
    delete_flag enum('Y', 'N') not null default 'N',
    primary key (income_id),
    index (user_id),
    index (category),
    index (delete_flag),
    index (created)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
