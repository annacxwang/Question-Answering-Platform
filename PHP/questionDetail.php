<!DOCTYPE html>
<!--Question and Answer detail page of the Q and A platform -->
<html>

    <title>Knowledge Universe - Question Detail</title>
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
#hyper{
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

.questionBox{
   /* border: 5px solid #dddddd;*/
    background-color:#eeeeee;
}
.userBar{
    text-align:right;
    font-style:italic;
}
.answerBtn{
    text-align:right;
}



    </style>

<?php

include ("connectdb.php");
$qid = $_GET["qid"];
$suid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];
//$followed = $_SESSION["followed"];
//$resed = $_SESSION["resed"];
$sort = $_GET["sort"];

// function to compute user status from points
function computeStatus($pt){
    if($pt >1000){
       return "Expert";
    }
    else if ($pt<500){
        return "Basic";
    }
    else{
        return "Advanced";
    }
}

if(isset($qid)){


    //top user info bar

    echo '<div class = "row">
    <div class="column" id = "logo">Knowledge Universe</div>
    <div class = "column" id="search-bar"> <form action="search.php?keyword='.$_GET["keyword"].' method="post">
    <textarea cols="40" rows="1" name="keyword" placeholder="Enter Search Keyword..."/></textarea>
    <input type="submit" value="Search">
    </form></div>';
    if(!isset($suid))
        {
            echo '<div class = "column" id="user"> <a href="login.php">login</a> <a href="register.php">register</a> </div></div>';
            }
    else{
            echo '<div class = "column" id="user"> Welcome, <a href="userProfile.php?uid='.$suid.'">'.$loginusername.'</a>
            <a href="postQuestion.php"> Post question</a>
            <a href="logout.php"> Logout </a></div></div>';

        //load user session info
        
        $followSession = $mysqli->prepare("select * from FollowSession where qid = $qid and uid = $suid");
        if(!$followSession->execute()){
            echo "Error description: ".($followSession -> error)."Returning to index page...";
            header("refresh: 999");
        };
        $followSession->bind_result($followed);
        if ($followSession->fetch()){
        $_SESSION['followed'] = 1;}
        else{
            $_SESSION['followed'] = 0;
        }
        $followSession->close();


        //set likeSession default 0
        $all = $mysqli->prepare("select aid from Answer A where A.qid = $qid ");
        if(!$all->execute()){
            echo "Error description: ".($all -> error)."Returning to index page...";
            header("refresh: 999");
        };
        $all->bind_result($aid);
        while($all->fetch()){
            $_SESSION['liked'.$aid] = 0;
        }
        $all->close();

        //load like session info
        
        $likeSession = $mysqli->prepare("select S.aid from LikeSession S ,Answer A where A.aid = S.aid and A.qid = $qid and S.uid = $suid");
        if(!$likeSession->execute()){
            echo "Error description: ".($likeSession -> error)."Returning to index page...";
            header("refresh: 999");
        };
        $likeSession->bind_result($aid);
        $likeSession->store_result();
        while($likeSession->fetch()){
            $_SESSION['liked'.$aid] = 1;
        }
        $likeSession->close();

        
    }

    //start of question information
    

    $question = $mysqli->prepare("Select T.tid,T.title,Q.uid,Q.title,Q.qbody,qtime,followcount,resolved,username,points from Topic T,Question Q, User U where T.tid = Q.tid and Q.uid = U.uid and qid = ?");
    $question->bind_param('i',$qid);

    if(!$question->execute()){
        echo "Error description: ".($question -> error)."Returning to index page...";
        header("refresh: 2; index.php");
    };
    $question->bind_result($tid,$topic,$uid,$title,$qbody,$qtime,$follow,$res,$username,$pt);
    if(!$question->fetch()){
        echo "<div>No question matching qid \"".$qid."\"</div>";
        $question->close();
        }
    else{
        $question->close();
        echo "<div id =\"hyper\">Topic: <a href = \"browse.php?tid=$tid\">$topic</a></div>";
        echo"<div class=\"questionBox\"><h2>Question #$qid </h2>";
        echo "<h1>$title</h1>";

        //Event Handler for Resolve, delete and follow buttons
        if(isset($_POST['res'])){
            //echo"res clicked";
            $new = 1-$res;
            $update = $mysqli->prepare("Update Question set resolved = $new where qid = $qid");
            $update->execute();
            $update->close();
            header("Refresh:0");
        }
        else if (isset($_POST['delQuestion'])){

            $delAnswer = "Delete from Answer where qid =?";
            $stmt = $mysqli->prepare($delAnswer);
            $stmt->bind_param("s", $qid);
            $stmt->execute();
            $stmt->close();

            $delQuestion = "Delete from Question where qid =?";
            $stmt = $mysqli->prepare($delQuestion);
            $stmt->bind_param("s", $qid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->close();

            echo "<script>alert('Deletion Complete');</script>";
            header("Refresh:0;index.php");
            
        }
        else if (isset($_POST['follow'])){
            //echo"follow clicked";
            
            if(isset($suid)){
                $sessionUpdate = $mysqli->prepare("insert into FollowSession(uid,qid) values(?,?)");
                $sessionUpdate->bind_param('ii',$suid,$qid);
                if(!$sessionUpdate->execute()){
                    //echo "Error description: ".($sessionUpdate -> error)."Returning to index page...";
                    $sessionUpdate->close();
                    $new = $follow-1;
                    $update = $mysqli->prepare("Update Question set followcount = $new where qid = $qid");
                    $update->execute();
                    $update->close();

                    $sessionUpdate = $mysqli->prepare("delete from FollowSession where uid = ? and qid = ?");
                    $sessionUpdate->bind_param('ii',$suid,$qid);
                    $sessionUpdate->execute();
                    $sessionUpdate->close();
                
                    header("Refresh:0");

                    }
                else{
                    $sessionUpdate->close();
                    $new = $follow+1;
                    $update = $mysqli->prepare("Update Question set followcount = $new where qid = $qid");
                    $update->execute();
                    $update->close();
                    header("Refresh:0");

                    }
                   

            }
            else{
                echo "<script>alert('Log in required to follow!');</script>";
            }

        }
        echo '<div id="hyper">';
        if($res == 1){
            echo("Resolved");
        }
        else{
            echo("Not resolved");
        }
        echo '</div>';

        if($_SESSION['followed'] == 1){
            $followText = "Unfollow";
        }
        else{
            $followText = "Follow";
        }

        echo "<div><form class =\"bts\" method=\"post\">
        <input type=\"submit\" name=\"follow\"
                value=\"$followText $follow\"/>
         ";
        
        
        // if current user asks this question
        if($suid == $uid){
            if($res == 1){
                $resText = "Unresolve";
            }
            else{
                $resText = "Resolve";
            }
            echo "<input type=\"submit\" name=\"res\"
                value=\"$resText \"/>";
                echo "<input type=\"submit\" name=\"delQuestion\"
                value=\"Delete \"/>";
        }
        echo "</form></div>";
        
        echo "<h3>$qbody</h3>";
        $status = computeStatus($pt);
        echo "<div class= \"userBar\">By $status <a href=\"userProfile.php?uid=$uid\">$username</a> Posted @ $qtime</div></div>";


        //
        if($sort == "time"){
            $selected = "Post time: Latest to Oldest";
            }
        else{
            // default sorting is by like count
            $selected = "Likes: High to Low";
            }
        
        
        echo "Sort by:<div class=\"dropdown\">
        <button class=\"dropbtn\">".$selected."</button>
        <div class=\"dropdown-content\">
          <a href=\"questionDetail.php?qid=".$qid."&sort=like\">Likes: High to Low</a>
          <a href=\"questionDetail.php?qid=".$qid."&sort=time\">Post time: Latest to Oldest</a>
          </div></div>";
        if($sort == "time"){
            $answers = $mysqli->prepare("select aid, A.uid, abody,atime, likes, username,points from Answer A, User U where A.uid = U.uid and qid = $qid order by atime DESC;");
        }
        else{
            $answers = $mysqli->prepare("select aid, A.uid, abody,atime, likes, username,points from Answer A, User U where A.uid = U.uid and qid = $qid order by likes DESC;");
        }


        $aidArr = array();
        $likesArr = array();
        $count = 0;

        


        if(!$answers->execute()){
            echo "Error description: ".($answers -> error)."Returning to index page...";
            header("refresh: 2; index.php");
        };
        $answers->bind_result($aid,$uid,$abody,$atime,$likes,$username,$pt);
        $answers->store_result();
        if($answers->num_rows == 0){
            echo "<div>No answers matching qid \"".$qid."\"</div>";
            $answers->close();
            }
        else{
            echo "<table>";
             while($answers->fetch()){
                $aidArr[$count] = $aid;
                $likesArr[$count] = $likes;
                $count++;
                echo "<tr><td><h3>Answer #$count (Overall #$aid):</h3>";
                echo "<div class=\"body\">$abody</div>";
                $status = computeStatus($pt);
                echo "<div class =\"userBar\">By $status <a href=\"userProfile.php?uid=$uid\">$username</a> Posted @ $atime</div>";
                
                
                if($_SESSION['liked'.$aid] == 1){
                    $likeText = "Unlike";
                }
                else{
                    $likeText = "Like";
                }
                echo "<div class =\"answerBtn\"> <form class =\"bts\" method=\"post\">
                <input type=\"submit\" name=\"liked$aid\"
                        value=\"$likeText $likes\"/>
                 ";
                 //show delete button if posted by current user

             if($suid == $uid){
                //echo "<form method=\"post\"><button name=\"delAns\" type=\"submit\" value=\"$aid\">Delete</button></form>";
                echo "<input type=\"submit\" name=\"delete$aid\"
                    value=\"Delete\"/>";
            }
            echo "</form>";
            echo "</div></td></tr>";
             }

        }
        echo "</table>";
        $answers->close();
        //echo "$count";


        //event handlers for all like and delete buttons clicked
        for($x = 0; $x < $count; $x++){
            $aid = $aidArr[$x];
            $sessionIndex = 'liked'.$aid;
            $deleteIndex = 'delete'.$aid;
            //echo "session is $sessionIndex";
        if (isset($_POST[$sessionIndex])){
            //echo"like clicked";


            if(isset($suid)){
                $sessionUpdate = $mysqli->prepare("insert into LikeSession(uid,aid) values(?,?)");
                $sessionUpdate->bind_param('ii',$suid,$aid);
                if(!$sessionUpdate->execute()){
                    //echo "Error description: ".($sessionUpdate -> error)."Returning to index page...";
                    $sessionUpdate->close();
                    $new = $likesArr[$x]-1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();

                    $sessionUpdate = $mysqli->prepare("delete from LikeSession where uid = ? and aid = ?");
                    $sessionUpdate->bind_param('ii',$suid,$aid);
                    $sessionUpdate->execute();
                    $sessionUpdate->close();
                
                    echo "<meta http-equiv='refresh' content='0'>";

                    }
                else{
                    $sessionUpdate->close();
                    $new = $likesArr[$x]+1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();
                    echo "<meta http-equiv='refresh' content='0'>";

                    }
                   

            }
            else{
                echo "<script>alert('Log in required to follow!');</script>";
            }

        }
        if (isset($_POST[$deleteIndex])){
            //echo "delete clicked";
            //echo "delete from Answer where aid= $aid";
            $deleteAns = $mysqli->prepare("delete from Answer where aid= $aid");
            if(!$deleteAns->execute()){
                echo "Error description: ".($deleteAns -> error)."Returning to index page...";
                //header("refresh: 2; index.php");
            };
            $deleteAns->close();
            $_SESSION["refresh"] = 1;
            //header("Refresh:0");
            echo "<meta http-equiv='refresh' content='0'>";

        }

        }
        //post answer
    if(isset($_SESSION["uid"])){
         

        if(strlen($_POST["abody"])>0) {

            //insert into database, note that aid is auto_increment and atime is set to current_timestamp by default
            if ($stmt = $mysqli->prepare("insert into Answer (uid, qid,abody) values (?,?,?)")) {
                $abody = $_POST["abody"];
                $stmt->bind_param("iis", $suid, $qid ,$abody);
                $stmt->execute();
              //echo "$abody \n";
                $stmt->close();
              //$user_id = htmlspecialchars($_SESSION["user_id"]);*/
              //echo "You will be returned to your blog in 3 seconds or click <a href=\"view.php?user_id=$user_id\">here</a>.";
             // header("Refresh:0");
              echo "<meta http-equiv='refresh' content='0'>";
            }  
          }
          //if not then display the form for posting answer
          //else {
            echo "<br /><br />\n";
            echo "<form method=\"post\">";
            //echo "<input type=\"text\" name=\"abody\" placeholder=\"Enter Answer Here...\">";
            echo "<textarea cols=\"40\" rows=\"3\" name=\"abody\" placeholder=\"Enter Answer Here...\"/></textarea>
            <input type=\"submit\" value=\"Post Answer\">
            </form> ";  
        
          //}

    }

    }

    

}
else{
    echo "Question id is not set!\nReturning to index page...";
    header("refresh: 2; index.php");
}
$mysqli->close();
?>
<div class = "Footer">
    <a href = "index.php">Index Page</a>
</div>
</html>