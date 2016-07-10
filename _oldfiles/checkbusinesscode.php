<?php
  include("config.php");
  session_start();
  if(isset($_SESSION['login_uid'])){
    if(isset($_GET['code'])){
      if(isset($_SESSION['user_status'])){
        $uid = $_SESSION['login_uid'];
        $sendCode = $_GET['code'];
        $sqlcmd = "SELECT PS_Code FROM printing_shop, user WHERE user.U_ID = $uid AND user.U_ID = printing_shop.U_ID;";
        $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
        $row = mysqli_fetch_row($dataRetrieve);
        if($row[0] == $sendCode){
          $_SESSION['user_status'] = "VERIFIED";
          $sqlupdate = "UPDATE user SET U_Status = 'VERIFIED' WHERE user.U_ID = $uid;";
          $updateData = mysqli_query($dbcon,$sqlupdate);
          if($updateData){
            print("OK");
          }else{
            print ("Error: Internal database error");
          }

        }else{
          print("Invalid code");
        }
      }else{
        print ("Error: Status is missing");
      }
    }else{
      print ("Error: No Code set");
    }
  }else{
    print ("Error: No Login Session");
  }
?>
