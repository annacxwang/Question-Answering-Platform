<!DOCTYPE html>
<!--browse by topic page of the Q and A platform -->
<html>
    <title>Knowledge Universe - Browse By Topic</title>

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
    td{
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        width: 10%;
    }
    th{
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
        width: 10%;
        font-size: 20px;
    }
    tr:nth-child(even){
        background-color: #dddddd;
        width: 10%;
    }

.high{
    font-size:30px;
    border:2px;
}
.low{
    font-size:20px;
}
    </style>
<?php
include ("connectdb.php");
$tid = $_GET["tid"];
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
if(isset($tid)){
    

    $topicDetail = $mysqli->prepare("Select title,higher_level_tid from Topic where tid = ?");
    $topicDetail->bind_param('s',$tid);

    if(!$topicDetail->execute()){
        echo "Error description: ".($topicDetail -> error)."Returning to index page...";
        header("refresh: 2; index.php");
    };
    $topicDetail->bind_result($topic,$higherTid);
    if(!$topicDetail->fetch()){
        echo "<div>No topic matching tid \"".$tid."\"</div>";
        $topicDetail->close();
        }
    else{
        $topicDetail->close();
        if($higherTid == null){
            //current tid is a higher level topic
            echo "<div><a href=\"browse.php\">All Topics</a></div>";

            echo"<h1>Questions under topic \"$topic\" </h1>"; 

            $questions = $mysqli->prepare("Select Q.tid,T.title,Q.qid, Q.title, qtime,followcount 
            from Question Q,Topic T where Q.tid = T.tid and (Q.tid = ? or T.higher_level_tid = ? ) 
            order by qtime DESC");
            $questions->bind_param('ss',$tid,$tid);
            if(!$questions->execute()){
                echo "Error description: ".($questions -> error)."Returning to index page...";
                header("refresh: 2; index.php");
            };
            $questions->bind_result($qtid,$topic,$qid, $title, $time,$follow);
            if(!$questions->fetch()){
                echo "<div>No result in this topic!</div>";
                }
            else{
                // Printing results in HTML
                echo "<table>\n";
                // table header + first line
                echo "<th>Topic</th><th>Title</th><th>Post Time</th><th>Follow Count</th></tr>\n";
                echo "<tr><td><a href=\"browse.php?tid=$qtid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>\n";
                // table body
                while ($questions->fetch()) {
                    echo "<tr><td><a href=\"browse.php?tid=$qtid\">$topic</a></td><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>\n";
                }
                echo "</table>\n";
            }
            
            $questions->close();
        }
        else{
            //current tid is a lower level topic

            echo "<div id = \"hyper\"><a href=\"browse.php?tid=$higherTid\">Higher Level Topic</a></div>";
           
            echo"<h1>Questions under topic \"$topic\" </h1>"; 
            
            $questions = $mysqli->prepare("Select Q.qid, Q.title, qtime,followcount 
                    from Question Q where Q.tid = ? order by qtime DESC");
            $questions->bind_param('s',$tid);
            if(!$questions->execute()){
                echo "Error description: ".($questions -> error)."Returning to index page...";
                header("refresh: 2; index.php");
            };
            $questions->bind_result($qid, $title, $time,$follow);
            if(!$questions->fetch()){
                echo "<div>No result in this topic!</div>";
                }
            else{
                // Printing results in HTML
                echo "<table>\n";
                // table header + first line
                echo "<th>Title</th><th>Post Time</th><th>Follow Count</th></tr>\n";
                echo "<tr><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td><td>$follow<td></tr>\n";
                // table body
                while ($questions->fetch()) {
                    echo "<tr><td><a href=\"questionDetail.php?qid=$qid\">$title</a></td><td>$time</td></td>$follow<td></tr>\n";
                }
                echo "</table>\n";
            }
            
            $questions->close();

        }
        }
    $mysqli->close();
    }
else{
    echo"<h2>List of topics:</h2><div><table>";
    $allTopic = $mysqli->prepare("select * from Topic");
    $allTopic->execute();
    $allTopic->bind_result($tid,$title,$higher);
    while ($allTopic->fetch()){
        if($higher == null){
            echo "</tr><tr>";
            $text = $title;
            echo "<th><a href = \" browse.php?tid=$tid\">$text</a></th></tr><tr>";
        }
        else{
            $text = $title;
            echo "<td><a href = \" browse.php?tid=$tid\">$text</a></td>";
        }
    }
    echo"</tr></table></div>";
}
?>
<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>
</html>