<?php
  /*
    To store user name into $_SESSION
  */
  include('config.php');
  session_start();// Starting Session

  // Storing Session
  $user_check = $_SESSION['login_uid'];

  // SQL Query To Fetch Complete Information Of User
  $sqlcmd = "SELECT U_Name FROM user WHERE U_ID= $user_check;";
  $ses_sql = mysqli_query($dbcon,$sqlcmd);
  $row = mysqli_fetch_row($ses_sql);
  $login_session = $row[0];
  if(!isset($login_session)){
    mysql_close($dbcon); // Closing Connection
    header('Location: index.php'); // Redirecting To Home Page
  }
?>
