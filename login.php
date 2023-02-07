<!DOCTYPE html>
<html>
  <head>
    <meta charset="windows-1251">
  </head>
  <body> 
<form name="Login" method="post">
  Name
  <input type="text" name="Name" value="">
  <input type="hidden" name="todo" value="log">
  Pass
  <input type="password" name="Pass" value="">
  <input type="submit" name="sub" value="Log in">
</form>
<?php
  include "func.php";
  if (isset($_POST["todo"]) && $_POST["todo"] == "log")
  {
    $pep = sql_req("SELECT * FROM users WHERE Name ='".$_POST["Name"]."'");
    if ($pep == [])
      sql_req("INSERT INTO users(Name, Pass) VALUES ('".$_POST["Name"]."', '".$_POST["Pass"]."')");
    else
    {
      if ($pep[0]["Pass"] != $_POST["Pass"])
        echo "wrong password";
      else
        header ("Location: game.php");
    }
  }
?>
  </body>
</html>