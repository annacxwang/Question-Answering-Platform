<!DOCTYPE html>
<!--Question and Answer detail page of the Q and A platform -->
<html>
<style>
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

<?php
include ("connectdb.php");
$qid = $_GET["qid"];
$suid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];
//$followed = $_SESSION["followed"];
//$resed = $_SESSION["resed"];
$sort = $_GET["sort"];

function computeStatus($pt){
    if($pt >1000){
       return "Expert";
    }
    else if ($pt<500){
        return "Advanced";
    }
    else{
        return "Intermidiate";
    }
}

if(isset($qid)){
    if(!isset($suid)){
        echo"<div><a href=\"login.php\">login</a> 
        <a href=\"register.php\">register</a> </div>";
    }
    else{
        echo"<div>Welcome, <a href=\"userProfile.php?uid=$uid\"> $loginusername </a></div>";
        echo "<div><a href=\"logout.php\"> Logout </a></div>";
    }
    echo"detail of question $qid ";

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

        

        $status = computeStatus($pt);
        echo "Topic: <a href = \"browse.php?tid=$tid\">$topic</a>";
        echo "<h1>$title</h1>";
        //echo "<div>$follow</div>";

        //$followText = "Follow";
        
        
        
        if(isset($_POST['res'])){
            //echo"res clicked";
            $new = 1-$res;
            $update = $mysqli->prepare("Update Question set resolved = $new where qid = $qid");
            $update->execute();
            $update->close();
            header("Refresh:0");
        }
        else if (isset($_POST['follow'])){
            //echo"follow clicked";
            if($_SESSION["followed"] ==1){
                //echo"follow -> unfollow";
                //if(isset($_POST['follow'])){
                    $new = $follow-1;
                    $update = $mysqli->prepare("Update Question set followcount = $new where qid = $qid");
                    $update->execute();
                    $update->close();
                    $_SESSION["followed"] = 0;
                    header("Refresh:0");
                //}
            } 
            else{
                if(isset($suid)){
                    //echo"unfollow -> follow";
                    $new = $follow+1;
                    $update = $mysqli->prepare("Update Question set followcount = $new where qid = $qid");
                    $update->execute();
                    $update->close();
                    $_SESSION["followed"] = 1;
                    header("Refresh:0");
                }
            else{
                echo "<script>alert('Log in required to follow!');</script>";
            }}
        }
        
        
        

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
        
        
        
        if($suid == $uid){
            if($res == 1){
                $resText = "Unresolve";
            }
            else{
                $resText = "Resolve";
            }
            echo "
            <input type=\"submit\" name=\"res\"
                value=\"$resText \"/>
            </form>";
        }
        if($res == 1){
            echo("Resolved");
        }
        else{
            echo("Not resolved");
        }
        echo "<div>$qbody</div>";
        echo "<div>By $status <a href=\"userProfile.php?uid=$uid\">$username</a> Posted @ $qtime</div>";


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
        if(!$answers->fetch()){
            echo "<div>No answers matching qid \"".$qid."\"</div>";
            $answers->close();
            }
        else{
            $aidArr[$count] = $aid;
            $likesArr[$count] = $likes;
            $count++;
            echo "<div>Answer #$aid:</div>";
            echo "<div>$abody</div>";
            $status = computeStatus($pt);
            echo "<div>By $status <a href=\"userProfile.php?uid=$uid\">$username</a> Posted @ $atime</div>";
            
            
            if($_SESSION['liked'.$aid] == 1){
                $likeText = "Unlike";
            }
            else{
                $likeText = "Like";
            }
            echo "<div><form class =\"bts\" method=\"post\">
            <input type=\"submit\" name=\"liked$aid\"
                    value=\"$likeText $likes\"/>
             ";
             while($answers->fetch()){
                $aidArr[$count] = $aid;
                $likesArr[$count] = $likes;
                $count++;
                echo "<div>Answer #$aid:</div>";
                echo "<div>$abody</div>";
                $status = computeStatus($pt);
                echo "<div>By $status <a href=\"userProfile.php?uid=$uid\">$username</a> Posted @ $atime</div>";
                
                
                if($_SESSION['liked'.$aid] == 1){
                    $likeText = "Unlike";
                }
                else{
                    $likeText = "Like";
                }
                echo "<div><form class =\"bts\" method=\"post\">
                <input type=\"submit\" name=\"liked$aid\"
                        value=\"$likeText $likes\"/>
                 ";

             }

        }
        $answers->close();
        //echo "$count";
        for($x = 0; $x < $count; $x++){
            $aid = $aidArr[$x];
            $sessionIndex = 'liked'.$aid;
            //echo "session is $sessionIndex";
        if (isset($_POST[$sessionIndex])){
            //echo"follow clicked";
            if($_SESSION[$sessionIndex] ==1){
                //echo"follow -> unfollow";
                //if(isset($_POST['follow'])){
                    $new = $likesArr[$x]-1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();
                    $_SESSION[$sessionIndex] = 0;
                    header("Refresh:0");
                //}
            } 
            else{
                if(isset($suid)){
                    //echo"unfollow -> follow";
                    $new = $likesArr[$x]+1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();
                    $_SESSION[$sessionIndex] = 1;
                    header("Refresh:0");
                }
            else{
                echo "<script>alert('Log in required to like!');</script>";
            }}
        }

        }

    }

    //post answer
    if(isset($_SESSION["uid"])){
        echo "<br /><br />\n";
        echo "<form method=\"post\">
        <input type=\"text\" name=\"abody\" placeholder=\"Enter Answer Here...\">
        <input type=\"submit\" value=\"Post Answer\">
        </form> ";    

    }
   

    if(is_string($_POST["abody"])){
        echo "inside if\n";
        $abody = $_POST["abody"];
        echo $abody;
        /*
        $stmt = $mysqli->prepare("insert into Answer (uid, qid,abody) values (?,?,?)");
        $stmt->bind_param("iis", $suid, $qid ,$abody);
        if(!$stmt->execute()){
            echo "Error description: ".($stmt -> error)."Returning to index page...";
            //header("refresh: 2; index.php");
        };
        $stmt->close();
        */
        $_POST["abody"]=0;
        header("Refresh:1");
        //INSERT INTO Answer(uid,qid,abody) VALUES(1,4,1,'body','2009-03-22 11:44:22',2333);

    }
    

}
else{
    echo "Question id is not set!\nReturning to index page...";
    header("refresh: 2; index.php");
}
$mysqli->close();
?>

</html>