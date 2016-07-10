<?php
  include('../config.php');
  session_start();
  if(isset($_SESSION['login_uid'])){
    if(isset($_GET['offlinetoken'])){
      $loginID = $_SESSION['login_uid'];
      $refreshToken = $_GET['offlinetoken'];
      $sqlcmd = "UPDATE user SET U_GRefreshToken = '$refreshToken' WHERE U_ID = $loginID;";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
      $numOfRows = $dbcon->affected_rows; //Retrieve number of row affected

      if($numOfRows > 0){
        $_SESSION['refresh_token'] = $refreshToken;
        header("Location: ../businessprofile.php");
      } else {
        echo "Error";
        //Error
      }
    } else {
      echo "No refresh token";
      //If no refresh token is set
    }
  } else {
    echo "No Login session";
    //If no login session is established
  }
?>
