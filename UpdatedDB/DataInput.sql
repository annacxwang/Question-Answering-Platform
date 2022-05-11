--
-- Users
--


INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES (1,'elavizadeh','adfgj2352','Student of WSE University',20,'dgdsgds@wse.edu','Manchester','New Hampshire','USA');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES (2,'tusizi','dsijef00','Experienced programmer at FAANG',1500,'tusizitighe@faang.com','Montain View','California','USA');
INSERT INTO User(uid,username,password,profile,points,email,country) VALUES (3,'csy1000','238usefd','Dominating the world',898,'sefq3344@dominator.com','Iceland');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES (4,'sheldor','moonpie226','B.S, M.S, M.A, Ph.D, Sc.D, and an I.Q. of 187',5566,'s.cooperphd@yahoo.com','Pasadena','California','USA');
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) VALUES (5,'afzal273','123efsdhg','HS student',33,'afe22ef@someHS.edu','Brooklyn','New York','USA');
INSERT INTO User(uid,username,password,profile,email,city,state,country) VALUES (6,'ycfszd897','qazxsw123','SH to NYC chemist','whoever@idk.com','Manhatton','New York','USA');
--
-- Topics
--
INSERT INTO Topic(tid,title) VALUES('T1','Computer Science');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T11','Data Structure','T1');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T12','Software Development','T1');
INSERT INTO Topic(tid,title) VALUES('T2','Physics');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T21','Theoretical Physics','T2');
INSERT INTO Topic(tid,title) VALUES('T3','Chemisty');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T31','Thermochemisty','T3');

--
-- Questions
--
INSERT INTO Question(qid,uid,tid,title,qtime,followcount) VALUES(1,1,'T21','What exactly are sub-atomic particles?','2009-03-11 09:22:33',233);
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount) VALUES(2,5,'T11','If advanced algorithms and data structures are never used in industry, then why learn them?','From my experience, advanced... ','2013-09-21 17:22:33',566);
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount) VALUES(3,5,'T12','What are the qualities of a good software developer?','As a CS student, I have always wanted..','2014-09-21 21:22:33',333);
INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount) VALUES(4,6,'T31','What is the future research area in thermo chem?','Thermo chem is a disciplinary field between phsics and chemistry...','2020-10-21 18:27:23',20);

--
-- Answers
--
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(1,4,1,'To answer it, we first must ask ourselves, what is physics?Physics comes from the ancient Greek word physika.Physika means the science of natural things. And it is there, in ancient Greece, that our story begins...','2009-03-22 11:44:22',2333);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(2,3,1,'According to wikipedia, sub-atomic particles are particles that compose atoms','2009-03-13 14:21:22',233);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(3,2,2,'If you are ok with just being a low-paid programmer and eventually exiting your profession to do something else, then do not learn algorithms and data structures. If computer science really well and truly excites you, then you know what to do.','2014-01-13 14:33:17',622);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(4,3,2,'The way I see it, it is not algorithms that I need in everyday life, but the ability to quickly analyze the problem and find a solution.','2013-11-13 14:33:17',388);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(5,4,3,'Although this is not quite my field, but l think computer scientist are alike physicsians in that...','2014-11-13 14:33:17',666);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES(6,2,3,' It’s hard to measure exactly what makes a good software developer. But what we can do is explore common characteristics and traits..','2014-12-07 09:18:54',238);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES (7,2,4,'Knowledge of the thermochemistry of molecules is of major importance in the chemical sciences and is essential to many technologies. Thermochemical data provide information on stabilities and reactivities of molecules that are used, for example, in modeling reactions occurring in combustion, the atmosphere, and chemical vapor deposition.','2021-03-13 14:21:22',233);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES (8,4,4,'Thermochemical data are a key factor in the safe and successful scale-up of chemical processes in the chemical industry. Despite compilations of experimental thermochemical data for many molecules, there are numerous species for which there are no data. In addition, the data in the compilations are sometimes incorrect.','2021-04-13 14:21:22', 356);
INSERT INTO Answer(aid,uid,qid,abody,atime,likes) VALUES (9,5,4,'Among the challenges will be extension of the methods to larger molecules, increased accuracy in predictions, and extension to heavier elements. The increase in computing power obtainable from new generations of computers, such as those with massively parallel architectures, will play an important role in meeting these challenges.','2021-05-13 14:21:22', 700);
