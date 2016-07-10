<?php
  include('config.php');
  session_start();
  if(isset($_SESSION['login_uid']) && !empty($_SESSION['login_uid'])) {
    $uid = $_SESSION['login_uid'];
    //if business owner want to view
    if(isset($_GET['business']) && !empty($_GET['business'])){
          $sqlcmd = "SELECT PR_ID,PR_Status, PR_Code, print_request.U_ID, PR_Price FROM user,printer, print_request, printing_shop WHERE user.U_ID = $uid AND PR_Status != 'COMPLETED' AND printing_shop.U_ID = user.U_ID AND printer.P_ID = print_request.P_ID AND printer.PS_ID = printing_shop.PS_ID";
          $printrequests = [];
          $printusers = [];
          $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
          while($row = mysqli_fetch_row($dataRetrieve)){
            $printrequest = array(
              "id" => $row[0],
              "name" => "",
              "status" => "",
              "code" => $row[2],
              "prstatus" => $row[1],
              "price" => $row[4]
            );
            array_push($printrequests,$printrequest);
            array_push($printusers,$row[3]);
          }
          $i = 0;
          foreach ($printusers as $id) {
            $sqlcmd = "SELECT U_Name, U_Status FROM user WHERE user.U_ID = $id;";
            $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
            $row = mysqli_fetch_row($dataRetrieve);
            $printrequests[$i]['name'] = $row[0];
            $printrequests[$i]['status'] = $row[1];
            $i++;
          }
          print json_encode($printrequests);
    }else{
      $sqlcmd = "SELECT PR_ID, Job_ID, PR_Name, PR_Price, PS_Name FROM user,printer, print_request, printing_shop WHERE print_request.U_ID = $uid AND user.U_ID = print_request.U_ID AND print_request.P_ID = printer.P_ID  AND printer.PS_ID = printing_shop.PS_ID";

      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
      $printrequests = [];
      while($row = mysqli_fetch_row($dataRetrieve)){
        $printrequest = array(
          'id' => $row[0],
          'jobid' => $row[1],
          'name' => $row[2],
          'price' => $row[3],
          'shop' => $row[4]
        );
        array_push($printrequests,$printrequest);
      }
      print json_encode($printrequests);
    }
  }
?>
