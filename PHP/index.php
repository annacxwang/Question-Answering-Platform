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
        <a href=\"postQuestion.php?\">post question</a>, <br /> 
        <br />
        <a href=\"logout.php\"> logout </a>. <br /> ";
}


echo "<br /><br />\n";
echo "<form action=\"search.php?keyword=".$_GET["keyword"]." method=\"post\">
<input type=\"text\" name=\"keyword\" placeholder=\"Enter Search Keyword...\">
<input type=\"submit\" value=\"Search\">
</form> \n";
echo "<a href=\"browse.php\"> Browse by Topic </a> <br />";

// view infomation
echo "You may view question here";

?>

</html>