<?php
  include("config.php");
  require_once("Message.php");
  if(isset($_GET['hpnum']) && !empty($_GET['hpnum'])){
    $hpnum = $_GET['hpnum'];
    $code = rand(1000,9999);
    $msgContent = "Your verification code is " . strval($code);
    $content = new Message();
    $content->sendMessage($hpnum,$msgContent);
    print($code);
  }
?>
