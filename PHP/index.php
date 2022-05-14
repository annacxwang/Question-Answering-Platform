<!DOCTYPE html>

<html>

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
#hyper{
    font-family: Monaco,monospace; 
    font-size: 16px;
}
.Footer{
    font-family: Monaco,monospace; 
    font-size: 16px;
}
    /* Stylesheet 1: */



    table {
        font-family: arial, sans-serif;
        align: center;
        border-collapse: collapse;
        width: 100%;
    }
    td,th{
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        width: 10%;
    }
    tr:nth-child(even){
        background-color: #dddddd;
        width: 10%;
    }
    </style>

    <title>Knowledge Universe - Index</title>


<?php

// global val for uid, username, and password(considering making it global or not)
include ("connectdb.php");

$userid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];
// $loginpassword = $_SESSION["password"];
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

echo "<a href=\"browse.php\" id =\"hyper\"> Browse by Topic </a> <br />";

// view infomation
echo "<h3>10 most recent questions:</h3>";

$questions = $mysqli->prepare("Select Q.tid,T.title,Q.qid, Q.title, qtime,followcount 
            from Question Q,Topic T where Q.tid = T.tid order by qtime DESC limit 10");
  if(!$questions->execute()){
      echo "Error description: ".($questions -> error)."Returning to index page...";
      header("refresh: 2; index.php");
    };
  $questions->bind_result($qtid,$topic,$qid, $title, $time,$follow);
    if(!$questions->fetch()){
        echo "<div>No question posted yet!</div>";
          }
       else{
                // Printing results in HTML
       echo "<table>\n";
      // table header + first line
      echo "<tr><th>Topic</th><th>Title</th><th>Post Time</th><th>Follow Count</th></tr>";
      echo "<tr><td><a href=\"browse.php?tid=$qtid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>\n";
      // table body
      while ($questions->fetch()) {
          echo "<tr><td><a href=\"browse.php?tid=$qtid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>\n";
                }
      echo "</table>\n";
              }
      $questions->close();

?>

</html>