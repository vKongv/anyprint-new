<?php
  include('usersession.php');
?>
  <script>
    var validToken = false;
    var username = "<?php echo $login_session;?>";
    var status = "";
  </script>
<?php
  if(isset($_SESSION['refresh_token'])){
    if($_SESSION['refresh_token'] == "ERROR"){
?>
      <script>
        validToken = true;
      </script>
<?php
      }
    }
    if(isset($_SESSION['user_status'])){
      ?>
      <script>
        status = "<?php echo $_SESSION['user_status'];?>";
      </script>
      <?php
    }else{
      header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Your Home Page</title>
    <!-- Import polymer HTML files-->
    <link rel="import" href="bower_components/paper-toolbar/paper-toolbar.html">
    <link rel="import" href="bower_components/iron-flex-layout/iron-flex-layout.html">
    <link rel="import" href="bower_components/paper-card/paper-card.html">
    <link rel="import" href="bower_components/paper-button/paper-button.html">
    <link rel="import" href="bower_components/paper-drawer-panel/paper-drawer-panel.html">
    <link rel="import" href="bower_components/paper-icon-button/paper-icon-button.html">
    <link rel="import" href="bower_components/iron-icons/iron-icons.html">
    <link rel="import" href="bower_components/paper-menu/paper-menu.html">
    <link rel="import" href="bower_components/paper-item/paper-item.html">
    <link rel="import" href="bower_components/paper-dialog/paper-dialog.html">
    <link rel="import" href="bower_components/paper-input/paper-input.html">
    <link rel="import" href="bower_components/paper-header-panel/paper-header-panel.html">
    <link rel="import" href="bower_components/neon-animation/neon-animated-pages.html">
    <link rel="import" href="bower_components/neon-animation/neon-animatable.html">
    <link rel="import" href="bower_components/font-roboto/roboto.html">
    <link rel="import" href="element/business-home.html">
    <link rel="import" href="element/business-add-printer.html">
    <link rel="import" href="element/business-manage-printer.html">
    <link rel="import" href="element/business-pending-print.html">

    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <style is="custom-style">
      body {
        font-family: Roboto;
      }
      paper-button.logout {
        background-color: #F62459;
      }
      paper-toolbar {
        background-color: #2C3E50;
      }
      paper-drawer-panel [main]{
        color: #000000;
      }
      paper-drawer-panel [drawer]{
        background-color: #2C3E50;
        color: white;
      }
      .item {
        margin-top: auto;
        margin-bottom: :auto;
        display: block;
        color: #22A7F0;
      }
      .primary {
        font-size: 36px;
        margin-top: 30px;
        margin-bottom: 10px;
      }
      .secondary{
        font-size: 24px;
      }
      .spacer{
        @apply(--layout-flex);
      }
      .logo{
        margin: auto;
      }
    </style>
  </head>

  <body class="fullbleed vertical layout">
    <template is="dom-bind" id="app">
      <paper-dialog with-backdrop modal id="loading">
        <h2>Loading...</h2>
      </paper-dialog>
      <iron-ajax
        id="checkcode"
        url="checkbusinesscode.php"
        handle-as="text"
        last-response="{{status}}"
        on-response="verifyCode"></iron-ajax>
      <paper-drawer-panel class="flex">
        <paper-header-panel drawer mode="seamed">
          <paper-toolbar drawer class="medium-tall">
            <div class="logo"><img src="res/anyprintlogo.png" width="120px" height="100px"></img></div>
          </paper-toolbar>
          <paper-menu selected={{select}} drawer>
            <paper-item>Home Page</paper-item>
            <paper-item>Add Printer</paper-item>
            <paper-item>Pending Print Request</paper-item>
            <paper-item>Manage Printer</paper-item>
          </paper-menu>
        </paper-header-panel>
        <paper-header-panel main mode="seamed">
          <paper-toolbar class="medium-tall">
            <paper-icon-button icon="menu" paper-drawer-toggle></paper-icon-button>
            <div class="item">
              <div class="primary">Welcome back</div>
              <div class="secondary">{{username}}</div>
            </div>
            <span class="spacer bottom"></span>
            <paper-button class="bottom logout" raised onclick="logout(event)">L O G O U T</paper-icon-button>
          </paper-toolbar>
          <neon-animated-pages id="pages" selected="{{select}}">
            <business-home id ="page1" user-id="<?php echo $_SESSION['login_uid'];?>"></business-home>
            <business-add-printer id='page2'></business-add-printer>
            <business-pending-print user-id="<?php echo $_SESSION['login_uid'];?>"></business-pending-print>
            <business-manage-printer id='page4' user-id="<?php echo $_SESSION['login_uid'];?>"></business-manage-printer>
          </neon-animated-pages>
        </paper-header-panel>
      </paper-drawer-panel>
      <paper-dialog id="verificationDialog" on-iron-overlay-closed="onDialogClose" with-backdrop modal>
          <h1>Enter Verification Code</h1>
          <div>
            <p>Your printing shop is NOT verified. Please enter the verification we sent to you. If you have not received the verification code, Please contact 017-3380115 (Kong)</p>
            <paper-input label="Enter verification code" id="inputCode" value={{inputCode}} maxlength="4"></paper-input>
          </div>
          <div class="buttons">
          <paper-button onclick="exit()">Cancel</paper-button>
          <paper-button autofocus onclick="verifyReg()">Continue...</paper-button>
      </paper-dialog>
    </template>
  </body>

  <script>
    //Set username
    var app = document.querySelector('#app');
    app.username = username;
    app.select = 0;
    var loaded = false;
    //When the element is finished loaded
    document.addEventListener('KLoad', function() {
      loaded = true;
      console.log("Listened");
      app.$.loading.close();
    }, false);
    var logout = function(event){
      window.location = "logout.php";
    }
    var exit = function(){
      window.location = "logout.php";
    }
    var verifyReg = function(){
      var params = {code: app.inputCode};
      app.$.checkcode.params = params;
      app.$.checkcode.generateRequest();
    }
    app.verifyCode = function(){
      var replystatus = " OK";
      var errorChecker = "Error:";
      if (app.status == replystatus){
        window.location = "businessprofile.php";
      }else if(app.status.indexOf(errorChecker) > -1){
        alert(app.status.split(errorChecker));
        window.location = "logout.php";
      }else{
        alert("Invalid Code!");
        window.location = "logout.php"
      }
    }
    app.onDialogClose = function(){
      window.location = "logout.php";
    }

    window.onload = function() {
      app = document.querySelector('#app');
      if(status == "NOT VERIFIED"){
        while (app.$.verificationDialog === 'undefined'){
          sleep(0.5);
        }
        app.$.verificationDialog.open();
      }
      if(!loaded && status != "NOT VERIFIED" ){
        while (app.$.loading === 'undefined'){
          sleep(0.5);
        }
        app.$.loading.open();
        }
    }
  </script>
</html>
