<?php
  include("config.php");
  require_once('Message.php');
  if(isset($_GET['jobid']) && !empty($_GET['jobid'])){
    if(isset($_GET['updateStatus'])) {
      if(isset($_GET['updateAmount'])){
        $jobid = $_GET['jobid'];
        $newStatus = $_GET['updateStatus'];
        $newAmount = $_GET['updateAmount'];
        //Check whether there is a change in the amount an status
        $statusUpdate = true;
        $amountUpdate = true;
        if($newStatus == "1"){
          $statusUpdate = false;
        }
        if($newAmount == "0"){
          $amountUpdate = false;
        }
        //Update database
        $dbUpdate = true; //To check whethere the update is ok or not
        if($statusUpdate){
          $sqlcmd = "UPDATE print_request SET PR_Status = 'COMPLETED' WHERE PR_ID = $jobid ";
          $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
          if(!$dataRetrieve){
            $dbUpdate = false;
          }
        }
        if($dbUpdate && $amountUpdate){
          $sqlcmd = "UPDATE print_request SET PR_Price = $newAmount WHERE PR_ID = $jobid ";
          $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
          if(!$dataRetrieve){
            $dbUpdate = false;
          }
        }
        if($dbUpdate){
          print "Update Successfully";
          if($amountUpdate){
            $sqlcmd = "SELECT U_HP FROM user,print_request WHERE print_request.PR_ID = $jobid AND print_request.U_ID = user.U_ID;";
            $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
            $row = mysqli_fetch_row($dataRetrieve);
            $hpNum = $row[0];
            $msgContent = "Your Print Request # " . $jobid . " is RM " . $newAmount;
            $newMessage = new Message();
            $newMessage->sendMessage($hpNum,$msgContent);
          }
        }else {
          print "Update fail";
        }
      }
    }
  }
?>
