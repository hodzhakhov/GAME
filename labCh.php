<!DOCTYPE html>
<html>
  <head>
    <title>Лабиринты</title>
  </head>
  <body>
    <?php
      @session_start();
    ?>
    <p>Выбор лабиринта</p>
    <br>
    <table border="1">
      <tr><td><a href="game.php">Лабиринт 1<?php @session_start(); $_SESSION["labC"] = 1 ?></a></td></tr>
      <tr><td><a href="game.php">Лабиринт 2<?php @session_start(); $_SESSION["labC"] = 2 ?></a></td></tr>
      <tr><td><a href="game.php">Лабиринт 3<?php @session_start(); $_SESSION["labC"] = 3 ?></a></td></tr>
    </table>  
  </body>
</html>