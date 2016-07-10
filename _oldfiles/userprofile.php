<?php
  include('usersession.php');
  if(isset($_GET['location'])){
    ?>
    <script>
      var displayLocation = "<?php echo $_GET['location'];?>";
    </script>
    <?php
  }else{
    ?>
    <script>
      var displayLocation = "All";
    </script>
    <?php
  }
?>
  <script>
    var username = "<?php echo $login_session;?>";
  </script>
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
    <link rel="import" href="bower_components/paper-header-panel/paper-header-panel.html">
    <link rel="import" href="bower_components/neon-animation/neon-animated-pages.html">
    <link rel="import" href="bower_components/neon-animation/neon-animatable.html">
    <link rel="import" href="bower_components/font-roboto/roboto.html">
    <link rel="import" href="element/user-home.html">
    <link rel="import" href="element/user-print-history.html">

    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <style is="custom-style">
      body {
        font-family: Roboto;
      }
      paper-button {
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
        font-size: 18px;
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
      <paper-drawer-panel class="flex">
        <paper-header-panel drawer mode="seamed">
          <paper-toolbar drawer class="medium-tall">
            <div class="logo"><img src="res/anyprintlogo.png" width="120px" height="100px"></img></div>
          </paper-toolbar>
          <paper-menu selected={{select}} drawer>
            <paper-item>Home Page</paper-item>
            <paper-item>Printing History</paper-item>
          </paper-menu>
        </paper-header-panel>
        <paper-header-panel main mode="seamed">
          <paper-toolbar class="medium-tall">
            <paper-icon-button icon="menu" paper-drawer-toggle></paper-icon-button>
            <div class="item">
              <div class="primary">Welcome back, {{username}}</div>
              <div class="secondary">Print Anywhere, Anytime</div>
            </div>
            <span class="spacer bottom"></span>
              <paper-button class="bottom" raised onclick="logout(event)">L O G O U T</paper-icon-button>
          </paper-toolbar>
          <neon-animated-pages id="pages" selected="{{select}}">
            <user-home></user-home>
            <user-print-history></user-print-history>
          </neon-animated-pages>
        </paper-header-panel>
      </paper-drawer-panel>
    </template>
  </body>

  <script>
    var app = document.querySelector("#app");
    app.select = 0;
    app.username = username;
    app.currentLocation = displayLocation;
    var logout = function(event){
      window.location = "logout.php";
    }
  </script>
</html>
