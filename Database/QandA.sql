--
-- Drop any if already present
--
drop table if exists User CASCADE;
drop table if exists Question CASCADE;
drop table if exists Answer CASCADE;
drop table if exists Topic CASCADE;

--
-- Create the Tables
--

Create table User(
    uid varchar(3) primary key,
    username varchar(124) not null,
    password varchar(24) not null,
    profile varchar(2000),
    points integer not null,
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
    qid varchar(3) primary key,
    uid varchar(3) not null,
    tid varchar(3) not null,
    title varchar(200) not null,
    qbody varchar(500),
    qtime timestamp not null,
    followcount integer not null,
    resolved boolean not null,
    bestAid varchar(3),
    foreign key (uid) references User(uid),
    foreign key (tid) references Topic(tid)
);


Create table Answer(
    aid varchar(3) primary key,
    uid varchar(3) not null,
    qid varchar(3) not null,
    abody varchar(2000) not null,
    atime timestamp not null,
    likes integer not null,
    foreign key (uid) references User(uid),
    foreign key (qid) references Question(qid)
);


--
-- Users
--


INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES ('U01','elavizadeh','adfgj2352','Student of WSE University',20,'dgdsgds@wse.edu','Manchester','New Hampshire','USA');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES ('U02','tusizi','dsijef00','Experienced programmer at FAANG',1500,'tusizitighe@faang.com','Montain View','California','USA');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES ('U03','csy1000','238usefd','Dominating the world',898,'sefq3344@dominator.com',NULL,NULL,'Iceland');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES ('U04','sheldor','moonpie226','B.S, M.S, M.A, Ph.D, Sc.D, and an I.Q. of 187',5566,'s.cooperphd@yahoo.com','Pasadena','California','USA');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES ('U05','afzal273','123efsdhg','HS student',33,'afe22ef@someHS.edu','Brooklyn','New York','USA');
--
-- Topics
--
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T1','Computer Science',Null);
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T11','Data Structure','T1');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T12','Software Development','T1');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T2','Physics',Null);
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T21','Theoretical Physics','T2');

--
-- Questions
--
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount,resolved,bestAid) VALUES('Q01','U01','T21','What exactly are sub-atomic particles?',NULL,'2009-03-11 09:22:33',233,1,'A01');
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount,resolved,bestAid) VALUES('Q02','U05','T11','If advanced algorithms and data structures are never used in industry, then why learn them?','From my experience, advanced... ','2013-09-21 17:22:33',566,1,'A03');
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount,resolved,bestAid) VALUES('Q03','U05','T12','What are the qualities of a good software developer?','As a CS student, I have always wanted..','2014-09-21 21:22:33',333,0,NULL);

--
-- Answers
--
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A01','U04','Q01','To answer it, we first must ask ourselves, what is physics?Physics comes from the ancient Greek word physika.Physika means the science of natural things. And it is there, in ancient Greece, that our story begins...','2009-03-22 11:44:22',2333);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A02','U03','Q01','According to wikipedia, sub-atomic particles are particles that compose atoms','2009-03-13 14:21:22',233);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A03','U02','Q02','If you are ok with just being a low-paid programmer and eventually exiting your profession to do something else, then do not learn algorithms and data structures. If computer science really well and truly excites you, then you know what to do.','2014-01-13 14:33:17',622);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A04','U03','Q02','The way I see it, it is not algorithms that I need in everyday life, but the ability to quickly analyze the problem and find a solution.','2013-11-13 14:33:17',388);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A05','U04','Q03','Although this is not quite my field, but l think computer scientist are alike physicsians in that...','2014-11-13 14:33:17',666);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES('A06','U02','Q03',' It’s hard to measure exactly what makes a good software developer. But what we can do is explore common characteristics and traits..','2014-12-07 09:18:54',238);



