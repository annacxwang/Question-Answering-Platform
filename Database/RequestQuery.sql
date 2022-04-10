-- Question 3 writing sql queries --

-- Q1
-- Create a new user account, 
-- together with username, email, password, city, state, country, and profile
INSERT INTO User(uid,username,password,profile,points,email,city,state,country) 
VALUES ('U06','ycfszd897','qazxsw123','SH to NYC chemist',0,'whoever@idk.com','Manhatton','New York','USA');

-- Q2
-- Insert a new question into the system, 
-- by a particular user and assigned it to a particular topic in the hierarchy.

-- INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T3','Chemisty',Null);
-- INSERT INTO Topic(tid,title,higher_level_tid) VALUES('T31','Thermochemisty','T2');

INSERT INTO Question(qid,uid,tid,title,qbody,qtime,followcount,resolved,bestAid) 
VALUES('Q04','U06','T31','What is the future research area in thermo chem?','Thermo chem is a disciplinary field between phsics and chemistry...','2020-10-21 18:27:23',20,0,NULL);

-- Q3
-- Write a query that computes for each user their current status (basic, advanced, or expert status)
-- based on their answers and your own chosen criteria for defining the status.

-- Point trigger
DELIMITER $$
create trigger pointUpdate
after insert or update on Answer
FOR EACH ROW
BEGIN
	declare checkaid varchar(3);

    select bestAid into checkaid
    from Answer A, Question Q
    where Q.qid = new.qid
    if (new.aid == checkaid)
    then
        update User
           set points = points + (new.likes * 1.25) - old.likes
         where uid = new.uid
    else
        update User
           set points = points + new.likes - old.likes
         where uid = new.uid
end;
$$
DELIMITER ;

-- Output Status
-- Create TEMPORARY table TStatus
-- Select uid, points
-- From User;

-- Alter table TStatus
-- Add Status varchar(10) ;

Select uid, if(points > 1000, "expert", if(points > 500, "advanced", "basic")) as Status
From User


-- Q4
-- For a given question (say identified by an ID), output all answers to the question in chronological order from first to last. 
-- Output the answer text and the time and date when it was posted, and whether an answer was selected as best answer.
Set givenqid = 'Q03'

Select A.aid, A.abody, A.atime, if(Q.bestAid == A.aid, "Best Answer", "Not Best Answer") as BestorNot
From Answer as A, Question as Q
Where A.qid = Q.qid and A.qid = givenqid 
Order by A.atime;

-- Q5
-- For each topic in the topic hierarchy, 
-- output the number of questions posted and total number of
-- answers posted within that topic.
select Q.tid, count(distinct Q.qid) as numQ, count(distinct A.aid) as numA
From Question Q, Answer A
Where Q.qid = A.qid
Group by Q.tid;

-- Q6
-- Given a keyword query, output
Create view checkAnswer as
select A.qid, count (A.aid) as CNT 
From Answer A, Question Q
Where A.qid = Q.qid and A.abody like "%to%"
Group by A.qid

select Q.qid,
(if(Q.title like "%function%",10,0) 
+ if(Q.qbody like "%function%", 5,0) 
+ () as RelevenceVal
From Question Q, checkAnswer C
Where Q.qid = C.qid
Order by RelevenceVal DESC
