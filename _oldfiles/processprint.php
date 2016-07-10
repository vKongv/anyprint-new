<?php
  include("config.php");
  require_once("gcp/GoogleCloudPrint.php");
  require_once 'gcp/Config.php';
  session_start();

  if(isset($_GET['copies']) && !empty($_GET['copies'])) {
    $copies = intval($_GET['copies']);
    $color = $_GET['color'];
    $range = $_GET['range'];

    $range_arr = str_split($range);
    $ticket_color;
    $ticket_range;

    //Set the color value for ticket
    switch ($color) {
      case '0':
        $ticket_color = "STANDARD_MONOCHROME";
        break;
      case '1':
        $ticket_color = "STANDARD_COLOR";
        break;
      default:
        echo "UNKNOWN COLOR";
        exit();
        break;
    }

    //Set the page range value for ticket
    $startpage;
    $endpage;
    $tempStart = "";
    $tempEnd = "";
    $midIndicator = false; //To indicate "-" in page range
    $error = false;
    $printall = false;
    $i = 0;
    foreach ($range_arr as $char) {
      //First element must be number
      if($i == 0){
        if(is_numeric($char)){
          if($char == "0"){
            $printall = true;
            break;
          }
          $tempStart = $tempStart + $char;
        }
        else{
          $error = true;
        }
        $i++;
        continue;
      }
      //Second element and above
      //Meaning still in the start page
      if(!$midIndicator){
        if(!is_numeric($char)){
          if($char == "-"){
            $midIndicator = true;
          }else{
            $error = true;
          }
        }else{
          $tempStart = $tempStart + $char;
        }
      }else{
        if(!is_numeric($char)){
          if($char == "-"){
            $error = true;
          }else{
            $error = true;
          }
        }else{
          $tempEnd = $tempEnd + $char;
        }
      }
      $i++;
    }
    if($error){
      header("Location: printoption.php?printer=".$_SESSION['user_printer']. "&color=". $_SESSION['user_color']. "&err=1");
    }
    if($tempEnd == ""){
      $tempEnd = $tempStart;
    }
    $startpage = intval($tempStart);
    $endpage = intval($tempEnd);

    //CJT format print ticket
    $ticket;
    if($printall){
      $ticket = array(
        "version" => "1.0",
        "print" => array(
          "vendor_ticket_item" => array(),
          "color" => array(
            "type" => $ticket_color,
          ),
          "copies" => array(
            "copies" => $copies
          ),
        ),
      );
    }else{
      $ticket = array(
        "version" => "1.0",
        "print" => array(
          "vendor_ticket_item" => array(),
          "color" => array(
            "type" => $ticket_color,
          ),
          "copies" => array(
            "copies" => $copies
          ),
          "page_range" => array(
            "interval" => array(
              array(
                "start" => $startpage,
                "end" => $endpage
              )
            ),
          ),
        ),
      );
    }
    $jsonTicket = json_encode($ticket);
    //Get token to access printer first
    $sqlcmd = "SELECT P_ID, U_GRefreshToken FROM user,printer,printing_shop WHERE printer.P_ID_G = '$_SESSION[user_printer]' AND printer.PS_ID = printing_shop.PS_ID AND printing_shop.U_ID = user.U_ID;";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    $refreshToken = $row[1];
    $printerid = $row[0];
    $refreshTokenConfig['refresh_token'] = $refreshToken;

    //Submit the print job
    $gcp = new GoogleCloudPrint();
    $token = $gcp->getAccessTokenByRefreshToken($urlconfig['refreshtoken_url'],http_build_query($refreshTokenConfig));
    $gcp->setAuthToken($token->access_token);
    $printjob = $gcp->sendPrintToPrinter($_SESSION['user_printer'], $_SESSION['user_jobtitle'],$_SESSION['user_file'],$jsonTicket);
    if($printjob['status']){
      $code = rand(1000,9999);
      $codeString = strval($code);
      $sqlcmd = "INSERT INTO print_request (PR_ID,Job_ID,PR_Name, PR_Status, PR_Time,PR_Code,P_ID,U_ID) values($_SESSION[user_prid],'$printjob[id]','$_SESSION[user_jobtitle]','$printjob[status]',now(),$codeString,$printerid,$_SESSION[login_uid])";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
      if($dataRetrieve){
          ?>
          <script>
            alert('Your print job has been submitted! Please take note of the verification code in your print receipt in next page');
          </script>";
          <?php
          unset($_SESSION['user_prid'], $_SESSION['user_jobtitle'],$_SESSION['user_prid'],$_SESSION['user_psid'],$_SESSION['user_printer']);
          header("Location: viewjobdetails.php?id=" . $printjob[id]);
      }
      unset($_SESSION['user_prid'], $_SESSION['user_jobtitle'],$_SESSION['user_prid'],$_SESSION['user_psid'],$_SESSION['user_printer']);
    }
    else{
      echo "Not ok";
      echo $printjob['errormessage'];
    }
  }

?>
