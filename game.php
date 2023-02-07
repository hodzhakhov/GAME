<!DOCTYPE html>
<html>
  <head>
    <meta charset="windows-1251">
  </head>
  <body> 
    <style>
      td
      {
        width: 60px;
        height: 60px;
      }
      .wall
      {
        background-image: url("brick.jpg");
      }
      .enemy
      {
        background-image: url("tom.jpg");
      }
      .player
      {
        background-image: url("jerry.png");
      }
      .floor
      {
        background-image: url("floor.jpg");
      }
    </style>
   <?php
     include "func.php";
     $lab = sql_req("SELECT * FROM levels WHERE id = 1");
     $lab = $lab[0];
     $f = file_get_contents($lab["FileName"]);
     $f = explode("\n", $f);
     $p_x = $p_y = $e_x = $e_y = $w_x = $w_y = 0;
     echo "<table>";
     for ($y = 0; $y < $lab["H"]; $y++)
     {
       echo "<tr>";
       for ($x = 0; $x < $lab["W"]; $x++)
       {
         if (isset($f[$y][$x]))
         {
           $cl = "";
           $cnt = "";  
           switch($f[$y][$x])
           {
             case '*':
               $cl = 'wall';
               break;
             case 'p':
               $cl = 'floor';
               $cnt = "<img width=40 src='jerry.png'/>";
	       $p_x = $x;
	       $p_y = $y;
               break;
             case 'e':
               $cl = 'floor';
               $cnt = "<img width=50 src='tom1.png'/>";
	       $e_x = $x;
	       $e_y = $y;
               break;
             case '+':
               $cl = 'floor';
	       $w_x = $x;
	       $w_y = $y;
               break;
             case ' ':
               $cl = 'floor';
               break;
           }
           echo "<td id = '$y:$x' class = '$cl'> $cnt </td>";
         }
       }
       echo "</tr>";
     }
     echo "</table>";
   ?>
  <script>

  <?php echo "var p_x = ".$p_x.", p_y = ".$p_y.", e_x = ".$e_x.", e_y = ".$e_y.", w_x = ".$w_x.", w_y = ".$w_y.", lab_h = ". $lab["H"].", lab_w = ". $lab["W"]."\n";
  ?>
  var end = -1;
  var lab = [];
  for (y = 0; y < lab_h; y++)
  {
    var row = [];
    for (x = 0; x < lab_w; x++)
    {
      var t = document.getElementById(y + ':' + x);
      if (t != null && t.getAttribute('class') == 'wall')
        row.push(1);
      else
        row.push(0);
    }
    lab.push(row);
  }

  var allow = ["ArrowLeft", "ArrowRight", "ArrowDown", "ArrowUp"];
  var dd = 
  {
    dx : {"ArrowLeft" : -1, "ArrowRight" : 1, "ArrowDown" : 0, "ArrowUp" : 0}, 
    dy : {"ArrowLeft" : 0, "ArrowRight" : 0, "ArrowDown" : 1, "ArrowUp" : -1} 
  };

  var s_x = p_x, s_y = p_y;
  document.body.onkeydown = 
    function(event)
    {
      if (allow.indexOf(event.code) == -1)
        return;
      var t = document.getElementById(p_y + ':' + p_x); 
      var cnt = t.innerHTML;
      var nx = p_x + dd.dx[event.code], ny = p_y + dd.dy[event.code];
      if (nx >= 0 && ny >= 0 && nx < lab_w && ny < lab_h && lab[ny][nx] == 0)
      {
        s_x = p_x;
        s_y = p_y;
        p_x = nx;                    	
        p_y = ny;
        if (e_x == p_x && e_y == p_y)
        {
          t.innerHTML = "";
          var t1 = document.getElementById(e_y + ':' + e_x); 
          var cnt1 = t1.innerHTML;
          t1.innerHTML = "";
          t1 = document.getElementById(e_y + ':' + e_x);
          t1.innerHTML = cnt1;
          end = 0;
          clearInterval(timer_id);
          setTimeout(EndGame, 300);
        }
        else if (p_x == w_x && p_y == w_y)
        {
          t.innerHTML = "";
          t = document.getElementById(p_y + ':' + p_x);
          t.innerHTML = cnt; 
          end = 1;
          clearInterval(timer_id);
          setTimeout(EndGame, 300);
        }
        else
        {
          t.innerHTML = "";
          t = document.getElementById(p_y + ':' + p_x);
          t.innerHTML = cnt;
        }
      }
    };
    var map = [];

    function NewMap(e_x, e_y)
    {
      map = [];
      for (y = 0; y < lab_h; y++)
      {
        var row = [];
        for (x = 0; x < lab_w; x++)
        {
          row.push(Infinity);
        }
        map.push(row);
      }
      map[p_y][p_x] = 0;
      while (true)
      {
        var fl = false;
        for (y = 0; y < lab_h; y++)
        {
          for (x = 0; x < lab_w; x++)
          {
            if (x - 1 >= 0 && lab[y][x - 1] == 0 && map[y][x] + 1 < map[y][x - 1])
            {
              map[y][x - 1] = map[y][x] + 1;
              fl = true;               
            }

            if (x + 1 < lab_w && lab[y][x + 1] == 0 && map[y][x] + 1 < map[y][x + 1])
            {
              map[y][x + 1] = map[y][x] + 1;
              fl = true;               
            }

            if (y - 1 >= 0 && lab[y - 1][x] == 0 && map[y][x] + 1 < map[y - 1][x])
            {
              map[y - 1][x] = map[y][x] + 1;
              fl = true;               
            }

            if (y + 1 < lab_h && lab[y + 1][x] == 0 && map[y][x] + 1 < map[y + 1][x])
            {
              map[y + 1][x] = map[y][x] + 1;
              fl = true;               
            }
          }
        } 
        if (fl == false)
          break;
      }               
    }

    function Move()
    {
      if (s_x != p_x || s_y != p_y)
      {
        NewMap(e_x, e_y);
        s_x = p_x;
        s_y = p_y; 
      }
      var b = Infinity, bx = e_x, by = e_y, h = 0;
      if (map[e_y][e_x - 1] < b && e_x > 0)
      {
        h = 1;
        b = map[e_y][e_x - 1];
      }
      if (map[e_y][e_x + 1] < b && e_x < lab_w - 1)
      {
        h = 2;
        b = map[e_y][e_x + 1];
      }
      if (map[e_y - 1][e_x] < b && e_y > 0)
      {
        h = 3;
        b = map[e_y - 1][e_x];
      }
      if (map[e_y + 1][e_x] < b && e_y < lab_h - 1)
      {
        h = 4;
        b = map[e_y + 1][e_x];
      }
      if (h == 1)
        bx = e_x - 1;
      if (h == 2)
        bx = e_x + 1;
      if (h == 3)
        by = e_y - 1;
      if (h == 4)
        by = e_y + 1;

      var t = document.getElementById(e_y + ':' + e_x); 
      var cnt = t.innerHTML;
      e_x = bx;                    	
      e_y = by;
      t.innerHTML = "";
      t = document.getElementById(e_y + ':' + e_x);
      t.innerHTML = cnt; 
      if (e_x == p_x && e_y == p_y)
      {
        end = 0;
        clearInterval(timer_id);
        setTimeout(EndGame, 300);
      }
    }

    NewMap(e_x, e_y);
    var timer_id = setInterval(Move, 500);

    function EndGame()
    {
      if (end == 0)
        alert("Lose");
      if (end == 1)
        alert("Win");
      window.location.href = 'login.php';
    }
  </script>
  </body>
</html>