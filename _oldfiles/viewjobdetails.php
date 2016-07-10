<?php
  include('config.php');
  require_once('gcp/GoogleCloudPrint.php');
  require_once('gcp/Config.php');

  session_start();
  if(isset($_GET['id']) && !empty($_GET['id'])) {
    $jobid = $_GET['id'];
    //Check if the order is completed or not
     $sqlcmd = "SELECT PR_Status FROM print_request WHERE Job_ID = '$jobid';";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    $status = $row[0];
    //If the status is not COMPLETED
    if($status != "COMPLETED"){
      $sqlcmd = "SELECT user.U_GRefreshToken FROM user,printer, print_request, printing_shop WHERE print_request.Job_ID = '$jobid' AND printer.P_ID = print_request.P_ID AND printer.PS_ID = printing_shop.PS_ID AND printing_shop.U_ID = user.U_ID;";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
      $row = mysqli_fetch_row($dataRetrieve);
      $token = $row[0];
      //Update job status
      $gcp = new GoogleCloudPrint();
      $refreshTokenConfig['refresh_token'] = $token;
      $token = $gcp->getAccessTokenByRefreshToken($urlconfig['refreshtoken_url'],http_build_query($refreshTokenConfig));
      $gcp->setAuthToken($token->access_token);
      $status = $gcp->jobStatus($jobid);
      $sqlcmd = "UPDATE print_request SET PR_Status = '$status' WHERE Job_ID = '$jobid' ";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    }
    //Select the print request to be diplay
    $sqlcmd = "SELECT PR_ID, PR_Name, PR_Price, PR_Code FROM print_request WHERE Job_ID = '$jobid'";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    ?>
    <script>
      var id = "<?php echo $row[0];?>";
      var name = "<?php echo $row[1];?>";
      var amount = "<?php echo $row[2];?>";
      var code = "<?php echo $row[3];?>";
      var status = "<?php echo $status;?>";
    </script>
    <?php
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>View Details</title>
    <!-- Import polymer HTML files-->
    <link rel="import" href="bower_components/paper-toolbar/paper-toolbar.html">
    <link rel="import" href="bower_components/iron-flex-layout/iron-flex-layout.html">
    <link rel="import" href="bower_components/paper-card/paper-card.html">
    <link rel="import" href="bower_components/paper-button/paper-button.html">
    <link rel="import" href="bower_components/paper-drawer-panel/paper-drawer-panel.html">
    <link rel="import" href="bower_components/paper-icon-button/paper-icon-button.html">
    <link rel="import" href="bower_components/paper-toast/paper-toast.html">
    <link rel="import" href="bower_components/paper-item/paper-item.html">
    <link rel="import" href="bower_components/paper-header-panel/paper-header-panel.html">
    <link rel="import" href="bower_components/font-roboto/roboto.html">

    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <style is="custom-style">
      body {
        font-family: Roboto;
      }
      .center{
        max-width: 350px;
        max-height: 400px;
        margin: auto;
        margin-top: 100px;
      }
      .indicator {
        margin-right: 40px;
        font-weight: bold;
      }
      #important {
        color: red;
        font-weight: bold;
      }
    </style>
  </head>

  <body class="fullbleed vertical layout">
    <template is="dom-bind" id="app">
      <paper-header-panel class="flex">
        <paper-toolbar>
          <div><h1> Print details # {{prid}} <h1></div>
        </paper-toolbar>
        <div class="center">
          <h2 style="color:grey;">Treat this as your digital receipt</h2>
          <table>
            <tr>
              <td><p class="indicator">ID:</p></td>
              <td><p id="important">#{{prid}}</p></td>
            </tr>
            <tr>
              <td><p class="indicator">Document:</p></td>
              <td><p>{{name}}</p></td>
            </tr>
            <tr>
              <td><p class="indicator">Verification Code:</p></td>
              <td><p id="important">{{code}}<p></td>
            </tr>
            <tr>
              <td><p class="indicator">Status:</p></td>
              <td><p id="important">{{status}}</p></td>
            </tr>
            <tr>
              <td><p class="indicator">Price:</p></td>
              <td><p>RM {{amount}}</p></td>
            </tr>
          </table>
        </div>
      </paper-header-panel>

    </template>
  </body>

  <script>
    var app = document.querySelector("#app");
    app.select = 0;
    app.prid = id;
    app.name = name;
    app.code = code;
    app.status = status;
    app.amount = amount;
  </script>
</html>
