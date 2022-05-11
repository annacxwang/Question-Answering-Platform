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

echo "You are logged out. You will be redirected";
  header("refresh: 1; index.php");
?>

</html>