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

// function to compute user status from points
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
     /* if(isset($_SESSION["refresh"])){
      if ($_SESSION["refresh"] == 1){
        //echo "refresh..";
        $_SESSION["refresh"] =0;
        header("refresh: 0.1");}

    }*/

    //top user info bar
    if(!isset($suid)){
        //$uri = substr($_SERVER['REQUEST_URI'],1);
        echo'<div><a href="login.php">login</a> 
        <a href="register.php">register</a> </div>';
    }
    else{
        echo"<div>Welcome, <a href=\"userProfile.php?uid=$suid\"> $loginusername </a></div>";
        echo "<div><a href=\"logout.php\"> Logout </a></div>";

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
            ";
        }
        echo "</form>";
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
        $answers->store_result();
        if($answers->num_rows == 0){
            echo "<div>No answers matching qid \"".$qid."\"</div>";
            $answers->close();
            }
        else{
             while($answers->fetch()){
                $aidArr[$count] = $aid;
                $likesArr[$count] = $likes;
                $count++;
                echo "<div>Answer #$count (Overall #$aid):</div>";
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
                 //show delete button if posted by current user

             if($suid == $uid){
                //echo "<form method=\"post\"><button name=\"delAns\" type=\"submit\" value=\"$aid\">Delete</button></form>";
                echo "<input type=\"submit\" name=\"delete$aid\"
                    value=\"Delete\"/>";
            }
            echo "</form>";

             }

        }
        $answers->close();
        //echo "$count";


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
/*

            if($_SESSION[$sessionIndex] ==1){
                //echo"follow -> unfollow";
                //if(isset($_POST['follow'])){
                    $new = $likesArr[$x]-1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();
                    $_SESSION[$sessionIndex] = 0;
                    $_SESSION["refresh"] = 1;
                    //header("Refresh:0");
                    echo "<meta http-equiv='refresh' content='0'>";
                //}
            } 
            else if(isset($suid)){
                    //echo"unfollow -> follow";
                    $new = $likesArr[$x]+1;
                    $update = $mysqli->prepare("Update Answer set likes = $new where aid = $aid");
                    $update->execute();
                    $update->close();
                    $_SESSION[$sessionIndex] = 1;
                    $_SESSION["refresh"] = 1;
                    //header("Refresh:0");
                    echo "<meta http-equiv='refresh' content='0'>";
                }
            else{
                echo "<script>alert('Log in required to like!');</script>";
            }*/
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

    


   /*

    if(is_string($_POST["abody"])){
        echo "inside if\n";
        $abody = $_POST["abody"];
        echo $abody;
        
        $stmt = $mysqli->prepare("insert into Answer (uid, qid,abody) values (?,?,?)");
        $stmt->bind_param("iis", $suid, $qid ,$abody);
        if(!$stmt->execute()){
            echo "Error description: ".($stmt -> error)."Returning to index page...";
            //header("refresh: 2; index.php");
        };
        $stmt->close();
        
        $_POST["abody"]=0;
        header("Refresh:1");
        //INSERT INTO Answer(uid,qid,abody) VALUES(1,4,1,'body','2009-03-22 11:44:22',2333);

    }
    //button to go back to index
    echo"<form action=\"index.php\" method=\"post\">
    <input type=\"submit\" value=\"Back\">
    </form>"; */

}
else{
    echo "Question id is not set!\nReturning to index page...";
    header("refresh: 2; index.php");
}
$mysqli->close();
?>
<form action="index.php" method="post">
    <input type="submit" value="Back">
    </form>
</html>