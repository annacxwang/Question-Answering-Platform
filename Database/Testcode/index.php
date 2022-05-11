<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php

include ("include.php");
$loginusername = $_SESSION["username"];

if(!isset($loginusername)) {
  echo "Welcome to the Questionary web, you are not logged in. <br /><br >\n";
  echo 'You may view the questions and related answers listed below, 
        in order to post a question you need to   
        <a href="register.php">register</a> if you don\'t have an account yet.';
  echo "\n";
}
else {
  $username = htmlspecialchars($loginusername);
  echo "Welcome $username. You are logged in.<br /><br />\n";
  echo 'You may view the blogs listed below, <a href="view.php?user_id=';
  echo htmlspecialchars($_SESSION["user_id"]);
  echo '">go to your blog</a>, or <a href="post.php">post on your blog</a>, or <a href="logout.php">logout</a>.';
  echo "\n";
}
echo "<br /><br />\n";
if ($stmt = $mysqli->prepare("select username, user_id from users order by username")) {
  $stmt->execute();
  $stmt->bind_result($username, $user_id);
  while ($stmt->fetch()) {
    echo '<a href="view.php?user_id=';
	echo htmlspecialchars($user_id);
	$username = htmlspecialchars($username);
	echo "\">$username's blog</a><br />\n";
  }
  $stmt->close();
  $mysqli->close();
}

?>

</html>