<?php
  include('config.php');
  if(isset($_GET['id']) && !empty($_GET['id'])) {
    $prid = $_GET['id'];
    $sqlcmd = "SELECT PS_Name, PS_Address, PS_OH, PS_CH, user.U_HP FROM user, print_request, printer, printing_shop WHERE PR_ID = '$prid' AND print_request.P_ID = printer.P_ID AND printer.PS_ID = printing_shop.PS_ID AND printing_shop.U_ID = user.U_ID LIMIT 1;";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    ?>
    <script>
      var psname = "<?php echo $row[0];?>";
      var psaddress = "<?php echo $row[1];?>";
      var psoh = "<?php echo $row[2];?>";
      var psch = "<?php echo $row[3];?>";
      var hp = "<?php echo $row[4];?>";
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
          <div><h1> Viewing {{psname}} <h1></div>
        </paper-toolbar>
        <div class="center">
          <h2 style="color:grey;">Printing Shop Details</h2>
          <table>
            <tr>
              <td><p class="indicator">Name:</p></td>
              <td><p>{{psname}}</p></td>
            </tr>
            <tr>
              <td><p class="indicator">Address:</p></td>
              <td><p>{{psaddress}}</p></td>
            </tr>
            <tr>
              <td><p class="indicator">Handphone:</p></td>
              <td><p id="important">{{hp}}<p></td>
            </tr>
          </table>
          <p>This shop open from <strong>{{psoh}}</strong> until <strong>{{psch}}</strong></p>
        </div>
      </paper-header-panel>

    </template>
  </body>

  <script>
    var opentime = psoh.split(":");
    var closetime = psch.split(":");
    var openTimeIdentifer = "";
    var closeTimeIdentifer = "";
    console.log(opentime);
    for (var i = 0; i < opentime.length; i++) {
      if(i == 0){
        if(parseInt(opentime[i]) == 12){
          openTimeIdentifer = "PM";
          opentime[i] = "12";
        }
        else if(parseInt(opentime[i]) == 0){
          openTimeIdentifer = "AM";
          opentime[i] = "12";
        }
        else if(parseInt(opentime[i]) > 12){
          openTimeIdentifer = "PM";
          var temptime = parseInt(opentime[i]) - 12;
          opentime[i] = temptime.toString();
        }
        else if(parseInt(opentime[i]) < 12){
          openTimeIdentifer = "AM";
        }
      }
    }

    for (var i = 0; i < closetime.length; i++) {
      if(i == 0){
        if(parseInt(closetime[i]) == 12){
          closeTimeIdentifer = "PM";
          closetime[i] = "12";
        }
        else if(parseInt(closetime[i]) == 0){
          closeTimeIdentifer = "AM";
          closetime[i] = "12";
        }
        else if(parseInt(closetime[i]) > 12){
          closeTimeIdentifer = "PM";
          var temptime = parseInt(closetime[i]) - 12;
          closetime[i] = temptime.toString();
        }
        else if(parseInt(closetime[i]) < 12){
          closeTimeIdentifer = "AM";
        }
      }
    }
    var app = document.querySelector("#app");
    app.psname = psname;
    app.psaddress = psaddress;
    app.psoh = opentime[0] + "." + opentime[1] + openTimeIdentifer;
    app.psch = closetime[0] + "." + closetime[1] + closeTimeIdentifer;
    app.hp = hp;
  </script>
</html>
