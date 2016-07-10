<?php
  if(isset($_GET['id']) && !empty($_GET['id'])) {
?>
    <script>
      var psid = "<?php echo $_GET['id'];?>";
    </script>
<?php
  }else{
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Select Printer</title>
    <!-- Import polymer HTML files-->
    <link rel="import" href="bower_components/paper-toolbar/paper-toolbar.html">
    <link rel="import" href="bower_components/iron-flex-layout/iron-flex-layout.html">
    <link rel="import" href="bower_components/paper-card/paper-card.html">
    <link rel="import" href="bower_components/paper-button/paper-button.html">
    <link rel="import" href="bower_components/paper-drawer-panel/paper-drawer-panel.html">
    <link rel="import" href="bower_components/paper-icon-button/paper-icon-button.html">
    <link rel="import" href="bower_components/iron-icons/iron-icons.html">
    <link rel="import" href="bower_components/iron-pages/iron-pages.html">
    <link rel="import" href="bower_components/paper-menu/paper-menu.html">
    <link rel="import" href="bower_components/paper-item/paper-item.html">
    <link rel="import" href="bower_components/paper-header-panel/paper-header-panel.html">
    <link rel="import" href="bower_components/neon-animation/neon-animated-pages.html">
    <link rel="import" href="bower_components/neon-animation/neon-animatable.html">
    <link rel="import" href="bower_components/font-roboto/roboto.html">
    <link rel="import" href="element/user-print-page-1.html">

    <script src="bower_components/webcomponentsjs/webcomponents-lite.js"></script>
    <style is="custom-style">
      body {
        font-family: Roboto;
      }
    </style>
  </head>

  <body class="fullbleed vertical layout">
    <template is="dom-bind" id="app">
      <paper-header-panel class="flex">
        <paper-toolbar>
          <div><h1> Select Printer <h1></div>
        </paper-toolbar>
        <div>
          <user-print-page-1 id="page1"></user-print-page-1>
        </div>
        <div>
        </div>
      </paper-header-panel>

    </template>
  </body>

  <script>
    //Set the shop id for the element
    document.addEventListener('finished_attached', function() {
      document.querySelector('#page1').shopid = psid;
    }, false);
    //Set the first page
    var app = document.querySelector("#app");
    app.select = 0;
  </script>
</html>
