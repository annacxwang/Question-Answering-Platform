--
-- Drop any if already present
--


drop table if exists Answer ;
drop table if exists Question ;
drop table if exists Topic ;
drop table if exists User ;
--
-- Create the Tables
--

Create table User(
    uid integer primary key auto_increment,
    username varchar(124) not null unique,
    password varchar(24) not null,
    profile varchar(2000),
    points integer not null default 0,
    email varchar(50) not null,
    city varchar(20),
    state varchar(20),
    country varchar(20)
);

Create table Topic(
    tid varchar(3) primary key,
    title varchar(30) not null,
    higher_level_tid varchar(3)
);

Create table Question(
    qid integer primary key auto_increment,
    uid integer not null,
    tid varchar(3) not null,
    title varchar(200) not null unique,
    qbody varchar(500),
    qtime timestamp not null default CURRENT_TIMESTAMP,
    followcount integer not null default 0,
    resolved boolean not null default 0,
    foreign key (uid) references User(uid),
    foreign key (tid) references Topic(tid)
);


Create table Answer(
    aid integer primary key auto_increment,
    uid integer not null,
    qid integer not null,
    abody varchar(2000) not null,
    atime timestamp not null default CURRENT_TIMESTAMP,
    likes integer not null default 0,
    
    foreign key (uid) references User(uid),
    foreign key (qid) references Question(qid)
);

Create table FollowSession(
    uid integer not null,
    qid integer not null,
    primary key(uid,qid)
);

Create table LikeSession(
    uid integer not null,
    aid integer not null,
    primary key(uid,aid)
);




