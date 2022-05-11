
-- Point trigger
-- Drop TRIGGER pointUpdate;
-- Drop TRIGGER pointInsert;
-- Drop TRIGGER pointDelete;
-- Drop TRIGGER QuestionDelete;


DELIMITER $$
create trigger QuestionDelete
before DELETE on Question
FOR EACH ROW
BEGIN 
    delete from Answer
    where Answer.qid = old.qid;
END
$$
DELIMITER ;


DELIMITER $$
create trigger pointUpdate
after UPDATE on Answer
FOR EACH ROW
BEGIN
	DECLARE checkaid integer;
    
    update User
    set points = points + new.likes - old.likes
    where User.uid = new.uid;
END
$$
DELIMITER ;

DELIMITER $$
create trigger pointInsert
after INSERT on Answer
FOR EACH ROW
BEGIN
   
    update User
    set points = points + new.likes
    where User.uid = new.uid;

END
$$
DELIMITER ;

DELIMITER $$
create trigger pointDelete
after DELETE on Answer
FOR EACH ROW
BEGIN
    update User
    set points = points - old.likes
    where User.uid = old.uid;

END
$$
DELIMITER ;


