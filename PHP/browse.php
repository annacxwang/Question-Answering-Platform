<!DOCTYPE html>
<!--browse by topic page of the Q and A platform -->
<html>
<div class = "Header">
    <title>Knowledge Universe - User Profile</title>
    <h1>Welcome to Knowledge Universe</h1>
</div>
<style>
.high{
    font-size:30px;
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

if(!isset($suid)){
    echo'<div><a href="login.php">login</a> 
    <a href="register.php">register</a> </div>';
}
else{
    echo"<div>Welcome, <a href=\"userProfile.php?uid=$suid\"> $loginusername </a></div> 
    <div><a href=\"postQuestion.php\"> Post question</a> <br /> </div>";
    echo "<div><a href=\"logout.php\"> Logout </a></div>";
}

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

            echo "<div><a href=\"browse.php?tid=$higherTid\">Higher Level Topic</a></div>";
           
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
    echo"list of topics";
    $allTopic = $mysqli->prepare("select * from Topic");
    $allTopic->execute();
    $allTopic->bind_result($tid,$title,$higher);
    while ($allTopic->fetch()){
        if($higher == null){
            $class ="high";
            $text = $title;
        }
        else{
            $class = "low";
            $text = "&ensp;--".$title;
        }
        echo "<div class = \"$class\"><a href = \" browse.php?tid=$tid\">$text</a></div>";
    }
}
?>
<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>
</html>