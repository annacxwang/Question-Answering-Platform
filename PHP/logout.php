<!DOCTYPE html>

<html>
    <title>Knowledge Universe - Log Out</title>
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
</div>

<?php
session_start();
unset($_SESSION["uid"]);
unset($_SESSION["username"]);
unset($_SESSION["password"]);
session_destroy();
echo '<div class = "row">
    <div class="column" id = "logo">Knowledge Universe</div>
    <div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
    <textarea cols="40" rows="1" name="keyword" placeholder="Enter Search Keyword..."/></textarea>
    <input type="submit" value="Search">
    </form></div>';
    echo '<div class = "column" id="user"> <a href="login.php">login</a> <a href="register.php">register</a> </div>';

    echo "</div>";

  if(isset($_SERVER['HTTP_REFERER'])){
  //echo $_SERVER['HTTP_REFERER'];
  header('Location: '.$_SERVER['HTTP_REFERER']);}
  else{
    echo "You are logged out. You will be redirected";
    header("refresh: 1; index.php");
  }
?>

</html>