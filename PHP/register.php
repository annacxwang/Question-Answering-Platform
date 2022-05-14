<!DOCTYPE html>
<!-- Register page of the Q and A platform -->
<html>

    <title>Knowledge Universe - Register</title>
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
    $suid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];

            //preset values
    if (empty($_POST["profile"])){
        $userprofile = NULL;
    }
    else{
        $userprofile = $_POST["profile"];
    }

    if (empty($_POST["city"])){
        $usercity = NULL;
    }
    else{
        $usercity = $_POST["city"];
    }
    if (empty($_POST["state"])){
        $userstate = NULL;
    }
    else{
        $userstate = $_POST["state"];
    }
    if (empty($_POST["country"])){
        $usercountry = NULL;
    }
    else{
        $usercountry = $_POST["country"];
    }
    
    echo '<div class = "row">
    <div class="column" id = "logo">Knowledge Universe</div>
    <div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
    <textarea cols="40" rows="1" name="keyword" placeholder="Enter Search Keyword..."/></textarea>
    <input type="submit" value="Search">
    </form></div>';


    function displayRegistrationForm(){
        echo "<h3>Enter your information below:(the ones with '*' are required fields)</h3>\n";
        echo "<form action=\"register.php\" method=\"POST\">";
        echo "Username(*): <input type=\"text\" name=\"username\" /> <br />
                    Password(*): <input type=\"password\" name= \"password\" /> <br />
                    Discription: <input type=\"text\" name= \"profile\" /> <br />
                    Email(*): <input type=\"text\" name= \"email\" placeholder =\"abc@example.com\"/> <br />
                    City: <input type=\"text\" name= \"city\" /> <br />
                    State: <input type=\"text\" name= \"state\" /> <br />
                    Country: <input type=\"text\" name= \"country\" /> <br />
                    <input type = \"hidden\" name =\"clicked\" />";
        echo "<input type=\"submit\" value= \"Register\" /> <br /></form>";
        echo " <div class = \"hyper\">Already have an account?<a href = \"login.php\">login</a></div>";
        echo '<div class = "Footer"> <a href ="index.php">Index Page</a></div>'; 
    }

    if(isset($loginusername)) 
    {
        echo '<div class = "column" id="user"> Welcome, <a href="userProfile.php?uid='.$suid.'">'.$loginusername.'</a>
        <a href="postQuestion.php"> Post question</a>
        <a href="logout.php"> Logout </a></div></div>';
        echo "You are already logged in. \n";
        echo "You will be redirected in 1 seconds or click <a href=\"index.php\">here</a>.\n";
        header("refresh: 1; index.php");
    }
    else
    {
        echo '<div class = "column" id="user"> <a href="login.php">login</a> <a href="register.php">register</a> </div></div>';
           
        //if the user have entered _all_ entries in the form, insert into database    
        if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"])) 
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
                    $stmt->close();
                    echo"<script>alert('User name already taken!');</script>";
                    displayRegistrationForm();
                    
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
                                $_POST["email"],
                                $usercity,
                                $userstate,
                                $usercountry);
                        $stmt->execute();
                        $stmt->store_result();
                        $stmt->close();

                    echo "Registration complete, click <a href=\"index.php\">here</a> to return to homepage.";
                    header("refresh:1; index.php");
                    
                    }		  
                }	 
            }
        
        }
        else if(isset($_POST["clicked"])){
            echo"<script>alert('Required fields Incomplete!');</script>";
            displayRegistrationForm(); }
        else
        {  
            displayRegistrationForm();
        }
    }

    $mysqli->close();

?>


</html>