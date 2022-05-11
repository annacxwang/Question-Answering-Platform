Drop TRIGGER pointUpdate;
DROP TRIGGER pointInsert;

DELIMITER $$
create trigger pointUpdate
after UPDATE on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid varchar(10);
    
    select bestAid into checkaid
    from Answer A, Question Q
    where Q.qid = new.qid;
    
    if (new.aid = checkaid)
    then
        update User
           set points = points + (new.likes * 1.25) - old.likes
         where uid = new.uid;
    else
        update User
           set points = points + new.likes - old.likes
         where uid = new.uid;
    END IF;
END
$$
DELIMITER ;

DELIMITER $$
create trigger pointInsert
after INSERT on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid varchar(10);
    
    select bestAid into checkaid
    from Answer A, Question Q
    where Q.qid = new.qid;
    
    if (new.aid = checkaid)
    then
        update User
           set points = points + (new.likes * 1.25)
         where uid = new.uid;
    else
        update User
           set points = points + new.likes
         where uid = new.uid;
    END IF;
END
$$
DELIMITER ;

UPDATE Question
Set bestAid = "A09"
WHERE qid = "Q04";

UPDATE Answer
Set likes = 800
WHERE aid = "A09";

