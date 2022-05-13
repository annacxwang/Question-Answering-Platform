<!DOCTYPE html>

<html>
<title>Knowledge Universe - User Profile</title>

<?php
    include ("connectdb.php");
    //$userid = $_SESSION["uid"];
    //$loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    // user close account display
    $suid = $_SESSION["uid"];
    $loginusername = $_SESSION["username"];
    

    if (isset($_GET["uid"])){
        $userid = $_GET["uid"];
    }else{
        $userid = $_POST["uid"];
    }

    if (isset($_GET["uid"]))
    {
        // user infomation display
        $user = "select username, profile, points,city,state,country from User where uid = ?";
        if ($stmt = $mysqli->prepare($user)) 
        {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($username, $profile, $points,$city,$state,$country);
            if ($stmt->num_rows > 0) 
            {
                if(!isset($suid)){
                    echo'<div><a href="login.php">login</a> 
                    <a href="register.php">register</a> </div>';
                }
                else{
                    echo"<div>Welcome, <a href=\"userProfile.php?uid=$suid\"> $loginusername </a></div> 
                        <div><a href=\"postQuestion.php\"> Post question</a> <br /> </div>";
                        echo "<div><a href=\"logout.php\"> Logout </a></div>";
                }
                $stmt->fetch();
                echo "<h2>User Profile of $username</h2>";

                echo '<div><table border="2" width="60%">';
      
                if ($points > 1000)
                {
                    $status = "Expert";
                }
                elseif($points > 500)
                {
                    $status = "Advanced";
                }
                else
                {
                    $status = "Basic";
                }

                echo "<tr>
                        <td> User points: </td>
                        <td> $points </td>
                        </tr>";
                echo "<tr>
                        <td> User status: </td>
                        <td> $status </td>
                        </tr>";
                echo "<tr>
                        <td> Location: </td>
                        <td> $city  $state $country </td>
                        </tr>";
                echo "<tr>
                        <td> Profile information: </td>
                        <td> $profile </td>
                        </tr>
                        </table>";
                echo "<br /> <br /></div>";
                $stmt->close();

                $questions = "select Q.qid, Q.title, Q.qbody, Q.qtime, T.tid,T.title, U.username, Q.followcount
                    from Question Q, Topic T, User U
                    where Q.tid = T.tid and Q.uid = U.uid and Q.uid = ? 
                    order by Q.qtime DESC";
                if ($stmt = $mysqli->prepare($questions))
                {
                    $stmt->bind_param("s", $userid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($qid, $title, $qbody, $qtime, $tid,$topic, $username,$follow);
                    if ($stmt->num_rows > 0)
                    {
                        echo "Questions asked by $username are listed below, <br />
                        you can click on the question id to view the question detail. <br />";
                        echo '<table border="2" width="60%">';
                        echo "<tr>";
                        echo "<th> Question topic </th>
                                <th> Question title </th> 
                                <th> Question body </th>
                                <th> Post time </th>
                                <th> Follow Count </th>
                                </tr>";
                        while($stmt->fetch())
                        {
                            $qbody = substr($qbody, 0, 50);
                            echo "<tr>";
                            echo "<td><a href=\"browse.php?tid=$tid\">$topic</a></td>
                                    <td> <a href= \"questionDetail.php?qid=$qid\">$title </a></td>
                                    <td> $qbody... </td> <td> $qtime </td><td> $follow </td>
                                    </tr>";
                        }
                        echo "</table>";
                        echo "<br /> <br />";
                        $stmt->close();
                    }
                    else
                    {
                        $stmt->close();
                        echo '<table border="2" width="60%">';
                        echo "<tr> <td>
                        $username hasn't asked any question yet <br />
                            
                            </td> </tr>";
                        echo "</table>";
                        echo "<br /> <br />";
                    }
                }
                $answers = "select A.qid, A.abody, A.atime, U.username, Q.title,T.tid,T.title,A.likes
                            from Answer A, User U, Question Q, Topic T
                            where Q.tid = T.tid and A.uid = U.uid and A.qid = Q.qid and A.uid = ? 
                            order by A.atime DESC";
                if ($stmt = $mysqli->prepare($answers))
                {
                    $stmt->bind_param("s", $userid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($qid, $abody, $atime, $username, $title,$tid,$topic,$likes);
                    if ($stmt->num_rows > 0)
                    {
                        echo "Question answered by $username are listed below, <br />
                        you can click on the question id to view the question detail. <br />";
                        echo '<table border="2" width="60%">';
                        echo "<tr>";
                        echo "<th> Question topic</th>
                                <th> Question title</th>
                                <th> Answer body </th>
                                <th> Post time </th>
                                <th> Likes received </th>
                                </tr>";
                        while($stmt->fetch())
                        {
                            $abody = substr($abody, 0, 50);
                            echo "<tr>";
                            echo "<td><a href=\"browse.php?tid=$tid\">$topic</a></td><td> <a href= \"questionDetail.php?qid=$qid\"> $title </a> </td> 
                                    <td> $abody... </td><td> $atime </td><td> $likes </td>
                                    </tr>";
                        }
                        echo "</table>";
                        echo "<br /> <br />";
                        $stmt->close();
                    }
                    else
                    {
                        $stmt->close();
                        echo '<table border="2" width="60%">';
                        echo "<tr> <td>
                            $username hasn't answered any question yet <br />
                            
                        ";
                        echo "</table>";
                        echo "<br /> <br />";
                    }
                }
                $following = "select Q.qid, Q.title, Q.qbody, Q.qtime, T.tid,T.title, U.username, Q.followcount
                from FollowSession F, Question Q, Topic T, User U
                where F.qid = Q.qid and Q.tid = T.tid and F.uid = U.uid and F.uid = ?
                order by Q.qtime DESC";
            if ($stmt = $mysqli->prepare($following))
            {
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($qid, $title, $qbody, $qtime, $tid,$topic, $username,$follow);
                if ($stmt->num_rows > 0)
                {
                    echo "Questions followed by $username are listed below, <br />
                    you can click on the question id to view the question detail. <br />";
                    echo '<table border="2" width="60%">';
                    echo "<tr>";
                    echo "<th> Question topic </th>
                            <th> Question title </th> 
                            <th> Question body </th>
                            <th> Post time </th>
                            <th> Follow Count </th>
                            </tr>";
                    while($stmt->fetch())
                    {
                        $qbody = substr($qbody, 0, 50);
                        echo "<tr>";
                        echo "<td><a href=\"browse.php?tid=$tid\">$topic</a></td>
                                <td> <a href= \"questionDetail.php?qid=$qid\">$title </a></td>
                                <td> $qbody... </td> <td> $qtime </td><td> $follow </td>
                                </tr>";
                    }
                    echo "</table>";
                    echo "<br /> <br />";
                    $stmt->close();
                }
                else
                {
                    $stmt->close();
                    echo '<table border="2" width="60%">';
                    echo "<tr> <td>
                    $username hasn't followed any question yet <br />
                        
                        </td> </tr>";
                    echo "</table>";
                    echo "<br /> <br />";
                }
            }

            $liking = "select A.qid, A.abody, A.atime, U.username, Q.title,T.tid,T.title,A.likes
                            from LikeSession L, Answer A, User U, Question Q, Topic T
                            where L.aid = A.aid and Q.tid = T.tid and A.uid = U.uid and A.qid = Q.qid and L.uid = ? 
                            order by A.atime DESC";
                if ($stmt = $mysqli->prepare($liking))
                {
                    $stmt->bind_param("s", $userid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($qid, $abody, $atime, $username, $title,$tid,$topic,$likes);
                    if ($stmt->num_rows > 0)
                    {
                        echo "Answers liked by $username are listed below, <br />
                        you can click on the question id to view the question detail. <br />";
                        echo '<table border="2" width="60%">';
                        echo "<tr>";
                        echo "<th> Question topic</th>
                                <th> Question title</th>
                                <th> Answer body </th>
                                <th> Post time </th>
                                <th> Likes received </th>
                                </tr>";
                        while($stmt->fetch())
                        {
                            $abody = substr($abody, 0, 50);
                            echo "<tr>";
                            echo "<td><a href=\"browse.php?tid=$tid\">$topic</a></td><td> <a href= \"questionDetail.php?qid=$qid\"> $title </a> </td> 
                                    <td> $abody... </td><td> $atime </td><td> $likes </td>
                                    </tr>";
                        }
                        echo "</table>";
                        echo "<br /> <br />";
                        $stmt->close();
                    }
                    else
                    {
                        $stmt->close();
                        echo '<table border="2" width="60%">';
                        echo "<tr> <td>
                            $username hasn't liked any answers yet <br />
                            
                        ";
                        echo "</table>";
                        echo "<br /> <br />";
                    }
                }

            }
            else
            {
                $stmt->close();
                echo "No information exist for user with user id $userid";
            }
        }
    }
    else{
        echo "The user did not exist. You will be directed to the main page";
        header("refresh: 1; index.php");
    }

    if(isset($_POST["uid"])){
        $userid = $_POST["uid"];
        

        $delAnswer = "Delete from Answer where uid =?";
        if ($stmt = $mysqli->prepare($delAnswer))
        {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->close();
            $delQuestion = "Delete from Question where uid =?";
            if ($stmt = $mysqli->prepare($delQuestion)){
                $stmt->bind_param("s", $userid);
                $stmt->execute();
                $stmt->store_result();
                $stmt->close();
                $delUser = "Delete from User where uid = ?";
                if ($stmt = $mysqli->prepare($delUser))
                {
                    $stmt->bind_param("s", $userid);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->close();

                    echo "You have been removed from our system and will be directed to our main page";
                    session_start();
                    unset($_SESSION["uid"]);
                    unset($_SESSION["username"]);
                    unset($_SESSION["password"]);
                    session_destroy();
                    header("refresh: 1; index.php");

                }
            }
        }
    }
    else
    {   if($suid == $_GET["uid"]){
        $tempuid = $_GET["uid"];
        echo "If you want to close your account, please click the following button";
        echo "<form action=\"userProfile.php\" method= \"POST\">";
        echo "<input type=\"hidden\" name=\"uid\" value=\"$tempuid\" /> <br />";
        echo "<input type=\"submit\" name=\"button\" value=\"Close account\" /> <br />";
        echo "</form>";}
    }

    $mysqli->close();


?>

<a href = "index.php">Index Page</a>

</html>