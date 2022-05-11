<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Questionary Website Example</title>

<?php

// global val for uid, username, and password(considering making it global or not)
include ("connectdb.php");

$userid = $_SESSION["uid"];
$loginusername = $_SESSION["username"];
// $loginpassword = $_SESSION["password"];

if(!isset($userid)) 
{
  echo "Welcome to the Questionary web, you are not logged in. <br />"; 
  echo "In order to post or follow a question or like an answer you need to 
        <a href=\"login.php\">login</a> 
        or 
        <a href=\"register.php\">register</a> 
        if you don't have an account yet.";
}
// logged in user view page
else 
{
  $username = htmlspecialchars($loginusername);
  $uid = htmlspecialchars($userid);
  echo "Welcome $username. You are logged in. <br />";
  echo "Here is your 
        <a href=\"userProfile.php?uid=$uid\"> user profile </a>, <br />
        You may
        <a href=\"postQuestion.php?uid=$uid\">post question</a>, <br /> 
        <br />
        <a href=\"logout.php\"> logout </a>. <br /> ";
}

// view infomation
echo "<br /><br />\n";
echo "You may search the question here";
echo "You may browse the question";
echo "You may view question here";

?>

</html>