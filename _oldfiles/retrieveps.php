<?php
  include 'config.php';
  date_default_timezone_set("Asia/Kuala_Lumpur");
  $time = (int) date("His");
  if(isset($_GET['q']) && !empty($_GET['q'])) {
    $sqlcmd = "";
    $location = $_GET['q'];
    if($location == "All"){
      $sqlcmd = "SELECT PS_ID, PS_Name, PS_Address, PS_OH, PS_CH FROM printing_shop, user WHERE printing_shop.U_ID = user.U_ID AND U_Status = 'VERIFIED' ;";
    }else{
      $sqlcmd = "SELECT PS_ID, PS_Name, PS_Address, PS_OH, PS_CH FROM printing_shop, user WHERE printing_shop.U_ID = user.U_ID AND U_Status = 'VERIFIED' AND PS_Area = '$location' ;";
    }
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $printingshops = array();
    while($row = mysqli_fetch_row($dataRetrieve)){
      $OH = (int) str_replace(":","",$row[3]);
      $CH = (int) str_replace(":","",$row[4]);
      //If closed
      if($time < $OH || $time > $CH){
        continue;
      }
      $printingshop = array(
        "id" => $row[0],
        "name" => $row[1],
        "address" => $row[2],
      );
      array_push($printingshops, $printingshop);
    }
    print json_encode($printingshops);
  }
  // } else{
  //   $message = array(
  //     'err' = '1',
  //     'errMsg' = 'No access token is set';
  //   )
  //   print json_encode($message);
  // }

?>
