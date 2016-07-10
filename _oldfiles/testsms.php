<?php
  include("config.php");
  require_once("Message.php");
  $test = new Message();
  echo json_encode($test->sendMessage("0179556908","Hello, Test Message"));
?>
