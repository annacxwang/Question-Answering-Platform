<!DOCTYPE html>

<html>
<div class = "Header">
    <title>Knowledge Universe - User Profile</title>
    <h1>Welcome to Knowledge Universe</h1>
</div>

<?php

include ("connectdb.php");


$sql1 = "select uid, username, password from User where username = ? and password = ?";

function displayLoginForm($url){
    echo "Enter your username and password below:<br />";
        echo "<form action=\"login.php\" method= \"POST\">";
        echo "Username: <input type=\"text\" name=\"username\" /><br />";
        echo "Password: <input type=\"password\" name= \"password\" /><br />";
        echo "<input type = \"hidden\" name = \"url\" value = $url />";
        echo "<input type=\"submit\" value= \"Submit\" /> <br /></form>";
        echo " <div>Don't have an account?<a href = \"register.php\">register</a></div>";
        echo "<div><a href = \"index.php\">Index Page</a></div>";
}



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

<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>

</html>