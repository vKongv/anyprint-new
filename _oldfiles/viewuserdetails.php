<?php
  include('config.php');
  if(isset($_GET['name']) && !empty($_GET['name'])) {
    $uname = $_GET['name'];
    $sqlcmd = "SELECT U_HP FROM user WHERE U_Name = '$uname';";
    $dataRetrieve = mysqli_query($dbcon,$sqlcmd);
    $row = mysqli_fetch_row($dataRetrieve);
    ?>
    <script>
      var uname = "<?php echo $uname;?>";
      var hp = "<?php echo $row[0];?>";
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
        margin-top: 50px;
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
          <div><h1> Viewing user: {{uname}} <h1></div>
        </paper-toolbar>
        <div class="center">
          <p>You can reach this user by calling <span id="important">{{hp}}</span></p>
        </div>
      </paper-header-panel>

    </template>
  </body>

  <script>
    var app = document.querySelector("#app");
    app.uname = uname;
    app.hp = hp;
  </script>
</html>
