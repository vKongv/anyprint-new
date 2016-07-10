<?php
  include ("config.php");
  session_start();
  if(isset($_SESSION['user_psid']) && !empty($_SESSION['user_psid'])) {
    $psid = $_SESSION['user_psid'];

    //Get Print Request ID
    $sqlcmd = "SELECT PR_ID FROM print_request;";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $numOfRow = mysqli_num_rows($dataRetrieve);
    $PRID = 300001 + $numOfRow;
    $_SESSION['user_prid'] = $PRID;

    //Get name and detect extension
    $target_dir = "tmp/";
    $oriname =  basename($_FILES["file"]["name"]);
    $target_file;
    $uploadOk = false;
    $status;
    $fileType = pathinfo($oriname,PATHINFO_EXTENSION);
    foreach($acceptTypes as $acceptType){
      if($fileType == $acceptType){
        $uploadOk = true;
        //Set file name = userid + prid
        $target_file = $target_dir . $_SESSION["login_uid"] . "_". $PRID . "." . $fileType;
        $_SESSION['user_file'] = $target_file;
        $_SESSION['user_jobtitle'] = $oriname;
        break;
      }
    }
    if($uploadOk){
      if (move_uploaded_file($_FILES["file"]["tmp_name"],$target_file)){
        header("Location: selectprinter.php?status=ok&id=".$psid);
      }
      else{
        header("Location: uploadfile.php?status=err&id=".$psid);
      }
    }else{
      header("Location: uploadfile.php?status=err&id=".$psid);
    }
  }else{
    print("PS_ID is not set");
  }
?>
