<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php
    include ("connectdb.php");
    // $userid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];

            //preset values
    if (empty($_POST["profile"])){
        $userprofile = "null";
    }
    else{
        $userprofile = $_POST["profile"];
    }
    if (empty($_POST["email"])){
        $useremail = "null";
    }
    else{
        $useremail = $_POST["email"];
    }
    if (empty($_POST["city"])){
        $usercity = "null";
    }
    else{
        $usercity = $_POST["city"];
    }
    if (empty($_POST["state"])){
        $userstate = "null";
    }
    else{
        $userstate = $_POST["state"];
    }
    if (empty($_POST["country"])){
        $usercountry = "null";
    }
    else{
        $usercountry = $_POST["country"];
    }
    
    if(isset($loginusername)) 
    {
        echo "You are already logged in. \n";
        echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
        header("refresh: 1; index.php");
    }
    else
    {
        //if the user have entered _all_ entries in the form, insert into database

        if(!empty($_POST["username"]) && !empty($_POST["password"])) 
        {
            $sql1 = "select uid from User where username = ?";
            //check if username already exists in database
            //$stmt = $mysqli->prepare($sql1);
            if ($stmt = $mysqli->prepare($sql1)) 
            {
                $stmt->bind_param("s", $_POST["username"]);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($uid);
                $stmt->fetch();
                if ($stmt->num_rows > 0) 
                {
                    echo "Username existed, please select a new username!";
                    header("refresh: 1; register.php");
                    $stmt->close();
                }
                //if not then insert the entry into database, note that uid is set by auto_increment
                else 
                {
                    $stmt->close();
                    $sql2 = "insert into 
                                User (username,password,profile,email,city,state,country) 
                                values (?,?,?,?,?,?,?)";
                    if ($stmt = $mysqli->prepare($sql2))
                    {
                        $stmt->bind_param(
                                "sssssss", 
                                $_POST["username"],
                                $_POST["password"], 
                                $userprofile,
                                $useremail,
                                $usercity,
                                $userstate,
                                $usercountry);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->close();
                        echo "Registration complete, click <a href=\"index.php\">here</a> to return to homepage.";
                        header("refresh: 1; index.php"); 
                    }		  
                }	 
            }
        
        }
        else
        {
            echo "Enter your information below: <br /> <br />\n";
            echo "<form action=\"register.php\" method=\"POST\">";
            echo "Username: <input type=\"text\" name=\"username\" /> <br />
                    Password: <input type=\"password\" name= \"password\" /> <br />
                    Discription: <input type=\"text\" name= \"profile\" /> <br />
                    Email: <input type=\"text\" name= \"email\" /> <br />
                    City: <input type=\"text\" name= \"city\" /> <br />
                    State: <input type=\"text\" name= \"state\" /> <br />
                    Country: <input type=\"text\" name= \"country\" /> <br />";
            echo "<input type=\"submit\" value= \"Submit\" /> <br />"; 
        }
    }

    $mysqli->close();

?>
<form action="index.php" method="post">
    <input type="submit" value="Back">
    </form>
</html>