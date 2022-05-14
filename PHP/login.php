<!DOCTYPE html>

<html>

    <title>Knowledge Universe - Login</title>

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


$sql1 = "select uid, username, password from User where username = ? and password = ?";

function displayLoginForm($url){
    echo "<h3>Enter your username and password below:</h3>";
        echo "<form action=\"login.php\" method= \"POST\">";
        echo "Username: <input type=\"text\" name=\"username\" /><br />";
        echo "Password: <input type=\"password\" name= \"password\" /><br />";
        echo "<input type = \"hidden\" name = \"url\" value = $url />";
        echo "<input type=\"submit\" value= \"Submit\" /> <br /></form>";
        echo " <div class = \"hyper\">Don't have an account?<a href = \"register.php\">register</a></div>";
        echo "<div class = \"Footer\"><a href = \"index.php\">Index Page</a></div>";
}
$suid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];

echo '<div class = "row">
    <div class="column" id = "logo">Knowledge Universe</div>
    <div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
    <textarea cols="40" rows="1" name="keyword" placeholder="Enter Search Keyword..."/></textarea>
    <input type="submit" value="Search">
    </form></div>';
    if(!isset($suid))
        {
            echo '<div class = "column" id="user"> <a href="login.php">login</a> <a href="register.php">register</a> </div>';
            }
    else{
            echo '<div class = "column" id="user"> Welcome, <a href="userProfile.php?uid='.$suid.'">'.$loginusername.'</a>
            <a href="postQuestion.php"> Post question</a>
            <a href="logout.php"> Logout </a></div>';
            }
    echo "</div>";

//if the user is already logged in, redirect them back to homepage
if(isset($_SESSION["username"])) 
{
    echo "You are already logged in. \n";
    echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";

    //header("refresh:1;".$_SERVER['HTTP_REFERER']);
    header("refresh: 1; index.php");
    //header('Location: '.$_SERVER['HTTP_REFERER']);
}
else 
{
    
    //if the user have entered both entries in the form, check if they exist in the database
    if(strlen($_POST["username"])>0 && strlen($_POST["password"])>0) 
    {
  
      //check if entry exists in database
        if ($stmt = $mysqli->prepare($sql1)) 
        {
            $stmt->bind_param("ss", $_POST["username"], $_POST["password"]);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($uid, $username, $password);
            //if there is a match set session variables and send user to homepage
            
            if ($stmt->fetch()) 
            {
                $_SESSION["uid"] = $uid;
                $_SESSION["username"] = $username;
                $_SESSION["password"] = $password;
                $_SESSION["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"]; //store clients IP address to help prevent session hijack
                
                //echo ("login successful");
                $url = $_POST["url"];
                //echo "posted $url";
                if(strlen($url)>0){
                    //echo "You will be redirected in 1 seconds or click <a href=\"$url\">here</a>.";
                    
                    header('Location: '.$url);
                }
                else{
                    echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.";
                    header("refresh:1; index.php");
                }
                 

            }
          //if no match then tell them to try again
            else 
            {
                sleep(1); //pause a bit to help prevent brute force attacks
                //echo "Your username or password is incorrect, click <a href=\"login.php\">here</a> to try again.";
                echo"<script>alert('Your username or password is incorrect!');</script>";
                $url = $_POST["url"];
        //echo "to post $url";
            displayLoginForm($url);
            }
        $stmt->close();
        $mysqli->close();
        }  
    }
    else if(isset($_POST["url"])){
        echo"<script>alert('Login Information Incomplete!');</script>";
        $url = $_POST["url"];
        //echo "to post $url";
        displayLoginForm($url);
    }
    //if not then display login form
    else 
    {    
        $url = $_SERVER['HTTP_REFERER'];
        displayLoginForm($url);
    }
}
?>


</html>