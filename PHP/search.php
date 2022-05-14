<!DOCTYPE html>
<!-- Search functionality of the Q and A platform -->
<html>

    <title>Knowledge Universe - Search Results</title>

    <style>
     * {
  box-sizing: border-box;
}
/* a:link { text-decoration: none; }
a:visited{color:color:#B22222;}
a:hover { text-decoration: underline; } */

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

    .TableWrapper {
        position: relative;
        overflow: auto;
    }

    .top, .bottom {
        background-color: #04AA6D;
        color: #ffffff;
        padding: 15px;
    }


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

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}
/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 100px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}


/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd;}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: #3e8e41;}

</style>
<body>



<?php
include ("connectdb.php");
$keyword = $_GET["keyword"];
$suid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];
$host = $_SERVER['HTTP_HOST'];


echo '<div class = "row">
    <div class="column" id = "logo">';
    //echo '<a href = "index.php" style = "color":#B22222;>Knowledge Universe</a></div>'
echo 'Knowledge Universe</div>';

    echo '<div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
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

echo"<h1>Search result of \"".$keyword."\" </h1>";

$sort = $_GET["sort"];


if($sort == "old"){
    $selected = "Post time: Oldest to Latest";
    }
else if ($sort == "late"){
    $selected = "Post time: Latest to Oldest";
    }
else{
    $selected = "Relavance: High to Low";
    }


echo "Sort by:<div class=\"dropdown\">
<button class=\"dropbtn\">".$selected."</button>
<div class=\"dropdown-content\">
  <a href=\"search.php?keyword=".$keyword."&sort=relavance\">Relavance: High to Low</a>
  <a href=\"search.php?keyword=".$keyword."&sort=late\">Post time: Latest to Oldest</a>
  <a href=\"search.php?keyword=".$keyword."&sort=old\">Post time: Oldest to Latest</a></div></div>";

if(isset($_GET["keyword"])){

    //create views for current keyword
    if(!isset($sort)){
    $dropAnswer = $mysqli->prepare("DROP VIEW if exists checkAnswer;");
    $dropAnswer->execute();
    $dropAnswer->close();
    $answerView = $mysqli->prepare("
    Create view checkAnswer as
    select A.qid, count(A.aid) as ACNT 
    From Answer A, Question Q
    Where A.qid = Q.qid and A.abody like \"%".$keyword."%\"
    Group by A.qid;");
   // $answerView->bind_param('s', "%".$keyword."%");
    if(!$answerView->execute()){
        echo "Error description: ".($answerView -> error)."Returning to index page...";
        header("refresh: 2; index.php");
    };
    $answerView->close();
//DROP VIEW if exists checkQuestion;
    $dropQuestion = $mysqli->prepare("DROP VIEW if exists checkQuestion;");
    $dropQuestion->execute();
    $dropQuestion->close();
    $questionView = $mysqli->prepare("
    Create view checkQuestion as 
    select Q.qid,
    (if(Q.title like \"%".$keyword."%\",10,0) 
    + if(Q.qbody like \"%".$keyword."%\", 5,0)) as QCNT
    From Question Q;");
    //$questionView->bind_param('ss', "%".$keyword."%","%".$keyword."%");
    //$questionView->execute();
    if(!$questionView->execute()){
        echo "Error description: ".($answerView -> error)."Returning to index page...";
        header("refresh: 2; index.php");
    };
    $questionView->close();
    }


    if($sort == "late"){
        //echo "late to old";
        $stmt = $mysqli->prepare("Select Topic.tid,Topic.title,Q.qid, Question.title, qtime,followcount
        From (checkAnswer A right join checkQuestion Q on A.qid = Q.qid), Question, Topic
        Where (ifnull(A.ACNT,0) + ifnull(Q.QCNT,0)) != 0 and Question.qid = Q.qid and Question.tid = Topic.tid
        Order by qtime DESC");
    }
    else if ($sort =="old"){
        //echo "old to late";
        $stmt = $mysqli->prepare("Select Topic.tid,Topic.title,Q.qid, Question.title, qtime,followcount
        From (checkAnswer A right join checkQuestion Q on A.qid = Q.qid), Question, Topic
        Where (ifnull(A.ACNT,0) + ifnull(Q.QCNT,0)) != 0 and Question.qid = Q.qid and Question.tid = Topic.tid
        Order by qtime ");
    }
    else{
        $stmt = $mysqli->prepare("Select Topic.tid,Topic.title,Q.qid, Question.title, qtime,followcount
        From (checkAnswer A right join checkQuestion Q on A.qid = Q.qid), Question, Topic
        Where (ifnull(A.ACNT,0) + ifnull(Q.QCNT,0)) != 0 and Question.qid = Q.qid and Question.tid = Topic.tid
        Order by (ifnull(A.ACNT,0) + ifnull(Q.QCNT,0)) DESC");
    }
    if(!$stmt->execute()){
        echo "Error description: ".($answerView -> error)."Returning to index page...";
        header("refresh: 2; index.php");
    };
    $stmt->bind_result($tid,$topic,$qid, $title, $time,$follow);

    if(!$stmt->fetch()){
        echo "<div>No result matching keyword \"".$keyword."\"</div>";
        }
    else{
        // Printing results in HTML
        echo "<table>\n";
        // table header + first line
        echo "<tr><th>Topic</th><th>Title</th><th>Post Time</th><th>Follow Count</th></tr>";
        echo "<tr><td><a href=\"browse.php?tid=$tid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>";
        // table body
        while ($stmt->fetch()) {
        echo "<tr><td><a href=\"browse.php?tid=$tid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow</td></tr>";
        }
        echo "</table>\n";
    }
    
    $stmt->close();
    $mysqli->close();
}
else{
    echo "Search keyword is not set!\nReturning to index page...";
    header("refresh: 2; index.php");
}

?>
<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>
</body>

</html>