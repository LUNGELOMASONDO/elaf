<?php
  require "db-connect.php";
  require "session_life.php";
  require "item.php";
  require "lostitem.php";
  require "founditem.php";

  session_start();
  session_life();

  if(isset($_SESSION['lfuser']))
  {
      if(isset($_GET['itemid']))
      {
        $itemid = (int)$_GET['itemid'];
        $lostitem = new LostItem;
        $lostitem->get_item_from_db($itemid);
        if($lostitem->isItem())
        {
          header("Content-Type: application/json; charset=UTF-8");
          echo $lostitem->get_json();
  
        }
      }
  }
?>
