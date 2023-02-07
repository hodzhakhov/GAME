<?php
  function sql_req ( $query )
  {
    $link = mysqli_connect('localhost', 'root', '', '2021_cl112_KhodzhakhovE_GAME');
    if ($err = mysqli_connect_error())
      die("Die:". $err );
    ;//echo $query;
    $res = mysqli_query($link, $query) or die(mysqli_error($link));
    if ($res === true)
      $a = [];  
    else
    { 
      $a = [];
      while ($row = mysqli_fetch_array($res))
        $a[] = $row;
    }
    mysqli_close($link);
    return $a;
  }
?>
