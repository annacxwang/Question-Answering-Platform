<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php
    include ("connectdb.php");
    //$userid = $_SESSION["uid"];
    //$loginusername = $_SESSION["username"];
    // $loginpassword = $_SESSION["password"];
    $userid = $_GET["uid"];
    if (isset($userid))
    {
        $sql1 = "select username, profile, points from User where uid = ?";
        if ($stmt = $mysqli->prepare($sql1)) 
        {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($username, $profile, $points);
            if ($stmt->num_rows > 0) 
            {
                $stmt->fetch();
                echo "$username basic information is displayed here:\n";
                echo '<table border="2" width="60%">';
      
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
                        <td> User status: </td>
                        <td> $status </td>
                        </tr>";
                echo "<tr>
                        <td> Profile information: </td>
                        <td> $profile </td>
                        </tr>
                        </table>";
                $stmt->close();
            }
            else
            {
                $stmt->close();
                echo "No information exist for user with user id $userid";
            }
        }
        $sql2 = "select Q.qid, Q.title, Q.qbody, Q.qtime, T.title, U.username
                    from Question Q, Topic T, User U
                    where Q.tid = T.tid and Q.uid = U.uid and Q.uid = ? 
                    order by Q.qtime DESC";
        if ($stmt = $mysqli->prepare($sql2))
        {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($qid, $title, $qbody, $qtime, $topic, $username);
            if ($stmt->num_rows > 0)
            {
                echo "Questions asked by $username is listed below, <br />
                you can click on the question id to view the question detail. <br />";
                echo '<table border="2" width="30%">';
                echo "<tr>";
                echo "<th> Question ID </th>
                        <th> Question topic </th>
                        <th> Question title </th> 
                        <th> Question body </th>
                        </tr>";
                while($stmt->fetch())
                {
                    echo "<tr>";
                    echo "<td> <a href= \"questionDetail.php?qid=$qid\"> $qid </a> </td> 
                            <td> $topic </td>
                            <td> $title </td>
                            <td> $qbody </td>
                            </tr>";
                }
                echo "</table>";
                $stmt->close();
            }
            else
            {
                $stmt->close();
                echo "No question has been asked, you can post a question by clicking <a href=\"postQuestion.php?\">here</a> <br />";
                echo "Or click <a href=\"index.php?\">here</a> back to the main page. <br />";
            }
        }
        $sql3 = "select A.aid, A.qid, A.abody, A.atime, U.username
                    from Answer A, User U
                    where A.uid = U.uid and A.uid = ? 
                    order by A.atime DESC";
        if ($stmt = $mysqli->prepare($sql3))
        {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($aid, $qid, $abody, $atime, $username);
            if ($stmt->num_rows > 0)
            {
                echo "Question answered by $username are listed below, <br />
                you can click on the question id to view the question detail. <br />";
                echo '<table border="2" width="30%">';
                echo "<tr>";
                echo "<th> Answer ID </th>
                        <th> Belonging question ID </th>
                        <th> Answer body </th>
                        </tr>";
                while($stmt->fetch())
                {
                    echo "<tr>";
                    echo "<td> $aid </td>
                            <td> <a href= \"questionDetail.php?qid=$qid\"> $qid </a> </td> 
                            <td> $abody </td>
                            </tr>";
                }
                echo "</table>";
                $stmt->close();
            }
            else
            {
                $stmt->close();
                echo "Haven't answered any question yet <br />";
                echo "Or click <a href=\"index.php?\">here</a> back to the main page. <br />";
            }
        }
    }
    else{
        echo "The user did not exist. You will be directed to the main page";
        header("refresh: 1; index.php");
    }
    $mysqli->close();


?>

<form action="index.php" method="post">
    <input type="submit" value="Back">
    </form>

</html>