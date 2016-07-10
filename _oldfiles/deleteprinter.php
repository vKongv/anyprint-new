<?php
  include('config.php');
  session_start();
  if(isset($_SESSION['login_uid']) && !empty($_SESSION['login_uid'])) {
    $uid = $_SESSION['login_uid'];
    if(isset($_GET['pid']) && !empty($_GET['pid'])) {
      $printerid = $_GET['pid'];
      //Search for any pending print request for the printer
      $sqlcmd = "SELECT PR_ID FROM print_request, printer WHERE printer.P_ID = $printerid AND printer.P_ID = print_request.P_ID AND PR_Status != 'COMPLETED';";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
      $numOfRows = mysqli_num_rows($dataRetrieve);
      $canDelete = true;
      if($numOfRows > 0){
        $canDelete = false;
      }
      if($canDelete){
        $sqlcmd = "UPDATE printer SET P_Deleted = 1 WHERE P_ID = '$printerid'";
        $deletePrinter = mysqli_query($dbcon,$sqlcmd);
        if($deletePrinter){
            header("Location: businessprofile.php?delete=true");
          }//end if($errMsg == "")
          else{
            header("Location: businessprofile.php?delete=false");
          }//end else
        }else{
          ?>
          <script>
            alert("Cannot delete printer! There is pending print request on the printer!");
            window.location = "businessprofile.php?delete=false";
          </script>
          <?php
        }
    }else{
      print('Invalid request');
    }
  }else{
    print('No user is currently logged in');
  }

?>
