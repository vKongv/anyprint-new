<?php
  include('config.php');
  require_once('gcp/GoogleCloudPrint.php');
  require_once("gcp/Config.php");
  session_start();
  if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    $name = $_GET['name'];
    $code = $_GET['code'];
    $status = $_GET['prstatus'];
    $userstatus =$_GET['status'];
    $isreceived = false;
    if($status == "COMPLETED"){
      $isreceived = true;
    }
    //Check job status in db
    //Update job status
    $sqlcmd = "SELECT Job_ID,PR_Status FROM print_request WHERE PR_ID = $id";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    $job_id_g = $row[0];
    $dbStatus = $row[1];
    if($dbStatus == "COMPLETED"){
      print ("This print request already COMPLETED.");
      exit();
    }else{
      $gcp = new GoogleCloudPrint();
      $refreshTokenConfig['refresh_token'] = $_SESSION['refresh_token'];
      $token = $gcp->getAccessTokenByRefreshToken($urlconfig['refreshtoken_url'],http_build_query($refreshTokenConfig));

      $gcp->setAuthToken($token->access_token);
      $status = $gcp->jobStatus($job_id_g);
      $sqlcmd = "UPDATE print_request SET PR_Status = '$status' WHERE Job_ID = '$job_id_g' ";
      $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    }
  }
  //If completed, then cannot update status
  if($isreceived){
    ?>
    <script>
      var statusSelection =["COMPLETED"];
    </script>
    <?php
  }else{?>
    <script>
      var statusSelection = ["COMPLETED", "<?php echo $status;?>"];
    </script>
    <?php
  }
?>
<script>
  var id = "<?php echo $id; ?>";
  var name = "<?php echo $name; ?>";
  var code = "<?php echo $code; ?>";
  var userstatus = "<?php echo $userstatus; ?>";
</script>

<!DOCTYPE html>
<html>
  <head>
    <title>Print Request Details</title>
    <!-- Import polymer HTML files-->
    <link rel="import" href="bower_components/paper-toolbar/paper-toolbar.html">
    <link rel="import" href="bower_components/iron-flex-layout/iron-flex-layout.html">
    <link rel="import" href="bower_components/paper-button/paper-button.html">
    <link rel="import" href="bower_components/iron-input/iron-input.html">
    <link rel="import" href="bower_components/paper-header-panel/paper-header-panel.html">
    <link rel="import" href="bower_components/paper-dropdown-menu/paper-dropdown-menu.html">
    <link rel="import" href="bower_components/paper-item/paper-item.html">
    <link rel="import" href="bower_components/paper-listbox/paper-listbox.html">
    <link rel="import" href="bower_components/font-roboto/roboto.html">

    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <style is="custom-style">
      body {
        font-size: 18px;
        font-family: Roboto;
      }
      .indicator {
        font-weight: bold;
        margin-right: 40px;
      }
      input{
        font-size: 18px;
        padding: 0.3em;
      }
      .number {
        width: 36px;
      }
      .number-medium {
        width: 72px;
      }
      .center{
        max-width: 400px;
        max-height: 500px;
        margin: auto;
        margin-top: 30px;
      }
      paper-button{
        color: white;
        width: 100%;
        background: #65C6BB;
      }
      .alert{
        color: red;
        font-weight: bold;
      }
      .ok{
        color: #8BC34A;
        font-weight: bold;
      }
    </style>
  </head>

  <body class="fullbleed vertical layout">
    <template is="dom-bind" id="app">
      <paper-header-panel class="flex">
        <paper-toolbar>
          <div><h1> Print Request # {{id}} <h1></div>
        </paper-toolbar>
        <div class="center">
          <form id="printfile" method="GET" action="updateprintdetails.php">
            <table>
              <tr>
                <td><p class="indicator">From: <p></td>
                <td><p>{{name}} <span id="userStatus">({{userstatus}})</span></p></td>
              </tr>
              <tr>
                <td><p class="indicator">ID: <p></td>
                <td><p>{{id}}</p></td>
              </tr>
              <tr>
                <td><p class="indicator">Verification Code: <p></td>
                <td><p>{{code}}</p></td>
              </tr>
              <tr>
                <td><p class="indicator">Status: <p></td>
                <td>
                  <paper-dropdown-menu label="Change Status">
                    <paper-listbox class="dropdown-content" selected="{{select}}">
                      <template is="dom-repeat" items="[[statuss]]" as="status">
                        <paper-item>[[status]]</paper-item>
                      </template>
                    </paper-listbox>
                </paper-dropdown-menu>
                </td>
              </tr>
              <tr>
                <td><p class="indicator">Amount<p></td>
                <td><input is="iron-input" class="number-medium" allowed-pattern="[.0-9]" value="0" bind-value={{amount}}></td>
              </tr>
            </table>
            <input type="hidden" name="jobid" id="jobid">
            <input type="hidden" name="updateStatus" id="updateStatus">
            <input type="hidden" name="updateAmount" id="updateAmount">
          </form>
          <paper-button raised on-click="submitform">U P D A T E</paper-button>
        </div>
      </paper-header-panel>
    </template>
  </body>

  <script>
    var app = document.querySelector("#app");
    app.statuss = statusSelection;
    app.select = 1;
    app.id = id;
    app.name = name;
    app.code = code;
    app.userstatus = userstatus;
    app.submitform = function(event){
      if(this.select != 0 && this.amount =="0"){
        alert("Nothing to update");
      }else{
        this.$.updateStatus.value = this.select;
        this.$.updateAmount.value = this.amount;
        this.$.jobid.value = this.id;
        this.$.printfile.submit();
      }
    }
    if(userstatus == "NOT VERIFIED"){
      app.$.userStatus.setAttribute("class", "alert");
    }else{
      app.$.userStatus.setAttribute("class", "ok");
    }
  </script>
</html>
