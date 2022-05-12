<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php
    include ("connectdb.php");
    $userid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    date_default_timezone_get();
    echo ($userid);

    if(isset($loginusername)) 
    {
        if (isset($_POST["title"]) && isset($_POST["qbody"]) && isset($_GET["tid"]))
        {
            $date = date('Y-m-d H:i:s');
            $sql2 = "insert into 
                    Question (uid,title,qbody,city,qtime) 
                    values (?,?,?,?,?)";
            if ($stmt = $mysqli->prepare($sql2))
            {
                echo "query prepared <br />";
                $stmt->bind_param(
                        "sssss", 
                        $userid,
                        $_GET["tid"], 
                        $_POST["title"],
                        $_POST["qbody"],
                        $date);
                $stmt->execute();
                $stmt->store_result();
                $stmt->close();
                echo "Question has been posted successfully, click <a href=\"index.php\">here</a> to return to homepage.";
                header("refresh: 1; index.php");
            }
            else
            {
                //display registration form
                echo "Enter your Question below: <br /> <br />\n";
                echo "<form action=\"postQuestion.php\" method=\"POST\">";
                echo "Question title: <input type=\"text\" name=\"title\" /> <br />
                        Question body: <input type=\"text\" name= \"qbody\" /> <br />";
                echo"Select belonging topics below";
                $allTopic = $mysqli->prepare("select * from Topic");
                $allTopic->execute();
                $allTopic->bind_result($tid,$title,$higher);
                while ($allTopic->fetch())
                {
                    if($higher == null)
                    {
                        $class ="high";
                        $tiltleinfo = $title;
                    }
                    else
                    {
                        $class = "low";
                        $tiltleinfo = "&ensp;--".$title;
                    }
                    echo "<div class = \"$class\"><a href = \" postQuestion.php?tid=$tid\">$tiltleinfo</a></div>";
                }
                echo "<input type=\"submit\" value= \"Submit\" /> <br />";
            
            }
        }
    }
    else
    {
        echo "You need to sign in to post a question, please go back to the main page to sign in, you will be direct to the main page.";
        header("refresh: 1; index.php");
    }


?>
<form action="index.php" method="post">
    <input type="submit" value="Back">
    </form>

</html>