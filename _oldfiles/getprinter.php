<?php
  include 'config.php';
  require_once 'gcp/Config.php';
  require_once 'gcp/GoogleCloudPrint.php';

  session_start();
  // if(isset($_SESSION['access_token']) && !empty($_SESSION['access_token'])) {
    if(isset($_GET['q']) && !empty($_GET['q'])) {
      $uid = $_SESSION['login_uid'];
      if($_GET['q'] == 'gcp'){
        if($_SESSION['refresh_token'] != "ERROR"){
          $gcp = new GoogleCloudPrint();

          $refreshTokenConfig['refresh_token'] = $_SESSION['refresh_token'];
          $token = $gcp->getAccessTokenByRefreshToken($urlconfig['refreshtoken_url'],http_build_query($refreshTokenConfig));
          $gcp->setAuthToken($token->access_token);
          $printers = $gcp->getPrinters();

          $sqlcmd = "SELECT P_ID_G FROM user,printer,printing_shop WHERE user.U_ID = $uid AND user.U_ID = printing_shop.U_ID AND printing_shop.PS_ID = printer.PS_ID AND P_Deleted = 0;";
          $dataRetrieve = mysqli_query($dbcon,$sqlcmd);

          while($row = mysqli_fetch_row($dataRetrieve)){
          	for($i=0; $i<count($printers);$i++){
          		if($printers[$i]['id'] == $row[0]){
          			$printers[$i]['added'] = 'disabled';
                $tempStatus = $printers[$i]['connectionStatus'];
                $secondSql = "UPDATE printer SET P_Status = '$tempStatus' WHERE P_ID_G = '$row[0]';";
                $updateTable = mysqli_query($dbcon,$secondSql);
          		}
          	}
          }
           print json_encode($printers);
         }else{
           $nothing = array();
           print json_encode($nothing);
         }
      }else if($_GET['q'] == 'local'){
        $sqlcmd = "SELECT P_ID, P_Name, P_Status FROM user,printer,printing_shop WHERE user.U_ID = $uid AND user.U_ID = printing_shop.U_ID AND printing_shop.PS_ID = printer.PS_ID AND P_Deleted = 0;";
        $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
        $printers = [];
        while($row = mysqli_fetch_row($dataRetrieve)){
          $printer = array(
            'id' => $row[0],
            'name' => $row[1],
            'status' => $row[2]
          );
          array_push($printers, $printer);
        }
        print json_encode($printers);
      }else if($_GET['q'] == 'printing'){
        if(isset($_GET['id']) && !empty($_GET['id'])) {
          $pid = $_GET['id'];
          $sqlcmd = "SELECT P_ID, P_Name, P_Status, P_ID_G, P_Color FROM printer, printing_shop WHERE printer.PS_ID = $pid AND printer.PS_ID = printing_shop.PS_ID AND P_Status = 'ONLINE' AND P_Deleted = 0;";
          $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
          $printers = [];
          while($row = mysqli_fetch_row($dataRetrieve)){
            $printer = array(
              'id' => $row[0],
              'name' => $row[1],
              'status' => $row[2],
              'id_g' => $row[3],
              'color' => $row[4]
            );
            array_push($printers, $printer);
          }
          if(count($printers) <= 0){
            $empty = array();
            print json_encode($empty);
          }else{
            print json_encode($printers);
          }
        }
      }
    }
?>
