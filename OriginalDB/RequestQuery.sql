-- Question 3 writing sql queries --

-- Q1
-- Create a new user account, 
-- together with username, email, password, city, state, country, and profile
INSERT INTO User(username,password,profile,email,city,state,country) 
VALUES ('ycfszd897','qazxsw123','SH to NYC chemist','whoever@idk.com','Manhatton','New York','USA');

-- Q2
-- Insert a new question into the system, 
-- by a particular user and assigned it to a particular topic in the hierarchy.

INSERT INTO Topic(tid,title) VALUES('T3','Chemisty');
INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T31','Thermochemisty','T2');

INSERT INTO Question(uid,tid,title,qbody,qtime,followcount,resolved) 
VALUES(6,'T31','What is the future research area in thermo chem?','Thermo chem is a disciplinary field between phsics and chemistry...','2020-10-21 18:27:23',20,0);

-- Q3
-- Write a query that computes for each user their current status (basic, advanced, or expert status)
-- based on their answers and your own chosen criteria for defining the status.

-- Point trigger
-- Drop TRIGGER pointUpdate;

DELIMITER $$
create trigger pointUpdate
after UPDATE on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid integer;
    
    select bestAid into checkaid
    from Question Q
    where Q.qid = new.qid;
    
    if (new.aid = checkaid)
    then
        update User
           set points = points + (new.likes * 1.25) - old.likes
         where User.uid = new.uid;
    else
        update User
           set points = points + new.likes - old.likes
         where User.uid = new.uid;
    END IF;
END
$$
DELIMITER ;

DELIMITER $$
create trigger pointInsert
after INSERT on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid integer;
    
    select bestAid into checkaid
    from Question Q
    where Q.qid = new.qid;
    
    if (new.aid = checkaid)
    then
        update User
           set points = points + (new.likes * 1.25)
         where User.uid = new.uid;
    else
        update User
           set points = points + new.likes
         where User.uid = new.uid;
    END IF;
END
$$
DELIMITER ;

DELIMITER $$
create trigger pointDelete
after DELETE on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid integer;
    
    select bestAid into checkaid
    from Question Q
    where Q.qid = old.qid;
    
    if (old.aid = checkaid)
    then
        update User
           set points = points - (old.likes * 1.25)
         where User.uid = old.uid;
    else
        update User
           set points = points - old.likes
         where User.uid = old.uid;
    END IF;
END
$$
DELIMITER ;

-- this trigger will update point at the moment when an answer is selected as the best answer

DROP TRIGGER if exists selectBest;

DELIMITER $$
create trigger selectBest
after UPDATE on Question
FOR EACH ROW
BEGIN
    
        DECLARE buid integer;
        DECLARE blikes integer;
    if(old.bestAid != new.bestAid and new.bestAid is not null)
    then
        select uid,likes into buid,blikes
        from Answer
        where Answer.aid = new.bestAid;

        update User
        set points = points + 0.25 * blikes
        where User.uid = buid;
    END IF;
END
$$
DELIMITER ;

-- Output Status
-- Create TEMPORARY table TStatus
-- Select uid, points
-- From User;

-- Alter table TStatus
-- Add Status varchar(10) ;

-- Test if the triggers work on user 6

INSERT INTO Answer(aid,uid,qid,abody,likes) VALUES(10,6,3,'some random thing',333)
UPDATE Answer SET likes = 222 WHERE aid = 10;
UPDATE Question Set resolved =1 ,BestAid = 10 where qid=3;
DELETE FROM Answer WHERE aid = 10;


Select uid, if(points > 1000, "expert", if(points > 500, "advanced", "basic")) as Status
From User


-- the following querys can be used to compute status from current answer table ignoring pre-stored point value in user table

create view notBestPoints(uid,nbp) as
Select User.uid, sum(likes) 
From Question, Answer, User
where Question.qid = Answer.qid and Answer.uid = User.uid and Answer.aid != BestAid
group by User.uid;
create view bestPoints(uid,bp) as
Select User.uid, sum(likes) 
From Question, Answer, User
where Question.qid = Answer.qid and Answer.uid = User.uid and Answer.aid = BestAid
group by User.uid;
create view totalPoints(uid,points) as
Select User.uid, if((nbp is not null and bp is not null),nbp + bp*1.25, if(nbp is not null ,nbp , if (bp is not null,bp*1.25,0))) 
From User left outer join notBestPoints on User.uid = notBestPoints.uid left outer join bestPoints on User.uid = bestPoints.uid;
Select uid, if(points > 1000, "expert", if(points > 500, "advanced", "basic")) as Status
From totalPoints;

-- Q4
-- For a given question (say identified by an ID), output all answers to the question in chronological order from first to last. 
-- Output the answer text and the time and date when it was posted, and whether an answer was selected as best answer.

-- Given QID is 2

Select A.aid, A.abody, A.atime, if(Q.bestAid = A.aid, "Best Answer", "Not Best Answer") as BestorNot
From Answer as A, Question as Q
Where A.qid = Q.qid and A.qid = 2
Order by A.atime;

-- I think from first to last means the oldest first and latest the last
-- change to q2 such that both best and not best are displayed

-- Q5
-- For each topic in the topic hierarchy, 
-- output the number of questions posted and total number of
-- answers posted within that topic.

-- this view gives the sum of q and a of each topic at all levels

create view singleLevel(stid,snumQ,snumA) as
select Q.tid, count(distinct Q.qid) , count(distinct A.aid) 
From Question Q, Answer A
Where Q.qid = A.qid
Group by Q.tid;

-- this view computes the sum of all lower level topics within a higher level topic

create view integration(itid,inumQ,inumA) as
select B.tid, sum(snumQ),sum(snumA)
from singleLevel, Topic as A, Topic as B
where stid = A.tid and A.higher_level_tid = B.tid
group by B.tid;

-- The total q and a count for a higher level topic is given by sum of all lower level topics within it plus q and a count for the higher level topic alone

select Topic.tid, if(inumQ* snumQ is not null , inumQ+snumQ, if(inumQ is not null, inumQ, if(snumQ is not null,snumQ,0))) as numQ,
if(inumA* snumA is not null , inumA+snumA, if(inumA is not null, inumA, if(snumA is not null,snumA,0))) as numA
From Topic left outer join integration on Topic.tid = integration.itid left outer join singleLevel on Topic.tid = singleLevel.stid;

-- Q6
-- Given a keyword query, output all questions that match the query and that fall into a particular topic,
-- sorted from highest to lowest relevance. (Select and define a suitable form of relevance – you could
-- match the keywords against the query title, the query text, or the query answers, and possibly give
-- different weights to these different fields.)
-- Special word is "to"

-- DROP VIEW checkAnswer;
-- DROP VIEW checkQuestion;

Create view checkAnswer as
select A.qid, count(A.aid) as ACNT 
From Answer A, Question Q
Where A.qid = Q.qid and A.abody like "%to%"
Group by A.qid;

Create view checkQuestion as 
select Q.qid,
(if(Q.title like "%to%",10,0) 
+ if(Q.qbody like "%to%", 5,0)) as QCNT
From Question Q;

-- this query will give results of all topics

Select Q.qid, (A.ACNT + Q.QCNT) as Relevenceval
From checkAnswer A, checkQuestion Q
Where A.qid = Q.qid
Order by Relevenceval DESC;



-- this query will give results under topic 'Computer Science'

Select tid into @cstid
from Topic
where title = 'Computer Science';

Select Q.qid, (A.ACNT + CQ.QCNT) as Relevenceval
From checkAnswer A, checkQuestion CQ, Topic T, Question Q
Where A.qid = CQ.qid and CQ.qid = Q.qid and ((Q.tid = @cstid) or( (Q.tid = T.tid)and (T.higher_level_tid = @cstid)))
Order by Relevenceval DESC;
