<!DOCTYPE html>

<html>

    <title>Knowledge Universe - Post Question</title>

    <style>
     * {
  box-sizing: border-box;
}

/* header row */
.column {
  float: left;
  padding: 10px;
}
/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
#logo{
    color:#B22222;
    font-family: Papyrus, fantasy; 
    font-size: 30px;
    width:30%;
}
#search-bar{
    color:#B22222;
    font-family: Papyrus, fantasy; 
    width:40%;
}
input[type=submit] {
    padding:5px 15px; 
    background:#B22222; 
    color:#ffffff;
    border:0 none;
    cursor:pointer;
    -webkit-border-radius: 5px;
    border-radius: 5px; 
    font-family: Monaco,monospace;
}
#user{
    color: #B22222;
    font-family: Monaco,monospace; 
    font-size: 16px;
    width:30%;
}
.Footer{
    font-family: Monaco,monospace; 
    font-size: 16px;
}
    /* Stylesheet 1: */
    body {
        font: 100%;
        font-family: arial, sans-serif;
        margin: 20px;
        line-height: 26px;
        border: 20px solid transparent;
    }
    .hyper{
    font-family: Monaco,monospace; 
    font-size: 16px;
}

    </style>
<?php
    include ("connectdb.php");
    $userid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    //$loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    //date_default_timezone_get();

    echo '<div class = "row">
    <div class="column" id = "logo">Knowledge Universe</div>
    <div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
    <textarea cols="40" rows="1" name="keyword" placeholder="Enter Search Keyword..."/></textarea>
    <input type="submit" value="Search">
    </form></div>';
    if(!isset($userid))
        {
            echo '<div class = "column" id="user"> <a href="login.php">login</a> <a href="register.php">register</a> </div>';
            }
    else{
            echo '<div class = "column" id="user"> Welcome, <a href="userProfile.php?uid='.$userid.'">'.$loginusername.'</a>
            <a href="postQuestion.php"> Post question</a>
            <a href="logout.php"> Logout </a></div>';
            }
    echo "</div>";

    if(isset($userid)) 
    {
        

        if (!empty($_POST["title"])  && !empty($_POST["tid"]))
        {
            $checkTitle = "select qid from Question where title = ?";
            //check if username already exists in database
            //$stmt = $mysqli->prepare($sql1);
            $stmt = $mysqli->prepare($checkTitle);
            $stmt->bind_param("s", $_POST["title"]);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) 
                {
                    $stmt->close();
                    echo"<script>alert('Question already asked!');</script>";
                    header("refresh: 0; index.php");
                    
                }
            else{
                if(empty($_POST["qbody"])){
                    $qbody = NULL;
                }
                else{$qbody = $_POST["qbody"];}
            $sql2 = "insert into 
                    Question (uid,tid,title,qbody) 
                    values (?,?,?,?)";
            
            if ($stmt = $mysqli->prepare($sql2))
            {
                $stmt->bind_param(
                        "ssss", 
                        $userid,
                        $_POST["tid"], 
                        $_POST["title"],
                        $qbody);
                if(!$stmt->execute()){
                    echo "Error description: ".($stmt -> error)."Returning to index page...";
                    $stmt->close();}
                else{
                echo "Question has been posted successfully, click <a href=\"index.php\">here</a> to return to homepage.";
                $stmt->close();
                header("refresh: 1; index.php");}
            }
        }
        }
        else if ((!empty($_POST["tid"]))){
            echo"<script>alert('Question title can not be empty!');</script>";
            //display registration form
            echo "<h3>Enter your Question below: </h3>\n";
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
                $tid = htmlspecialchars($tid);
                $title = htmlspecialchars($title);
                $higher = htmlspecialchars($higher);

                $tidinfo = $tid;
                $titleinfo = $title;
                echo "<option value=$tid>$titleinfo</option>";
            }
            echo "</select> <br />";
                    
            echo "<input type=\"submit\" value= \"Submit\" /> <br />";

        }
        else
        {
            //echo "Please input all the required field";
            //header("refresh: 1; postQuestion.php");

            //display registration form
            echo "<h3>Enter your Question below: </h3>\n";
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
                $tid = htmlspecialchars($tid);
                $title = htmlspecialchars($title);
                $higher = htmlspecialchars($higher);
                
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
<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>

</html>