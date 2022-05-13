<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<title>Logout</title>

<?php
session_start();
unset($_SESSION["uid"]);
unset($_SESSION["username"]);
unset($_SESSION["password"]);
session_destroy();


  if(isset($_SERVER['HTTP_REFERER'])){
  //echo $_SERVER['HTTP_REFERER'];
  header('Location: '.$_SERVER['HTTP_REFERER']);}
  else{
    echo "You are logged out. You will be redirected";
    header("refresh: 1; index.php");
  }
?>

</html>