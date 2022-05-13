<!DOCTYPE html>

<html>
<title>Knowledge Universe - Post Question</title>

<?php
    include ("connectdb.php");
    $userid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    //$loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    date_default_timezone_get();

    if(isset($userid)) 
    {
        echo"<div>Welcome, <a href=\"userProfile.php?uid=$userid\"> $loginusername </a></div> ";
        echo "<div><a href=\"logout.php\"> Logout </a></div>";

        if (!empty($_POST["title"]) && !empty($_POST["qbody"]) && !empty($_POST["tid"]))
        {
            $date = date('Y-m-d H:i:s');
            $sql2 = "insert into 
                    Question (uid,tid,title,qbody,qtime) 
                    values (?,?,?,?,?)";
            
            if ($stmt = $mysqli->prepare($sql2))
            {
                $stmt->bind_param(
                        "sssss", 
                        $userid,
                        $_POST["tid"], 
                        $_POST["title"],
                        $_POST["qbody"],
                        $date);
                $stmt->execute();
                $stmt->store_result();
                echo "Question has been posted successfully, click <a href=\"index.php\">here</a> to return to homepage.";
                $stmt->close();
                header("refresh: 1; index.php");
            }
        }
        else
        {
            //echo "Please input all the required field";
            //header("refresh: 1; postQuestion.php");

            //display registration form
            echo "Enter your Question below: <br /> <br />\n";
            echo "<form action=\"postQuestion.php\" method=\"POST\">";
            echo "Question title: <input type=\"text\" name=\"title\" /> <br />
                    Question body: <input type=\"text\" name= \"qbody\" /> <br />";
            echo "Select corresponding topic field:";
            echo "<select name=\"tid\">";
            $allTopic = $mysqli->prepare("select * from Topic");
            $allTopic->execute();
            $allTopic->bind_result($tid,$title,$higher);
            while ($allTopic->fetch())
            {
                $tidinfo = $tid;
                $titleinfo = $title;
                echo "<option value=$tid>$titleinfo</option>";
            }
            echo "</select> <br />";
                    
            echo "<input type=\"submit\" value= \"Submit\" /> <br />";
            //echo"<br /> Reference topic id is shown below <br />";
        }  
    }
    else
    {
        echo "You need to sign in to post a question, please go back to the main page to sign in, you will be direct to the main page.";
        header("refresh: 1; index.php");
    }


?>
<a href = "index.php">Index Page</a>

</html>