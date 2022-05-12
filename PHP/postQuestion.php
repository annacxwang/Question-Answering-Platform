<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php
    include ("connectdb.php");
    $userid = $_SESSION["uid"];
    //$loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    date_default_timezone_get();

    if(isset($userid)) 
    {
        if (isset($_POST["title"]) && isset($_POST["qbody"]) && isset($_POST["tid"]))
        {
            echo "enter all input checked";
            $date = date('Y-m-d H:i:s');
            $sql2 = "insert into 
                    Question (uid,tid,title,qbody,qtime) 
                    values (?,?,?,?,?)";
            
            if ($stmt = $mysqli->prepare($sql2))
            {
                echo "query prepared <br />";
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
                //display registration form
                echo "Enter your Question below: <br /> <br />\n";
                echo "<form action=\"postQuestion.php\" method=\"POST\">";
                echo "Question title: <input type=\"text\" name=\"title\" /> <br />
                        Question body: <input type=\"text\" name= \"qbody\" /> <br />
                        Topic id: <input type=\"text\" name= \"tid\" /> <br />";
                echo "<input type=\"submit\" value= \"Submit\" /> <br />";
                echo"<br /> Reference topic id is shown below <br />";
                $allTopic = $mysqli->prepare("select * from Topic");
                $allTopic->execute();
                $allTopic->bind_result($tid,$title,$higher);
                while ($allTopic->fetch())
                {
                    if($higher == null)
                    {
                        $class ="high";
                        $tidinfo = $tid;
                        $tiltleinfo = $title;
                    }
                    else
                    {
                        $class = "low";
                        $tidinfo = $tid;
                        $tiltleinfo = "&ensp;--".$title;
                    }
                    echo "<div class = \"$class\">$tid: $tiltleinfo</div>";
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