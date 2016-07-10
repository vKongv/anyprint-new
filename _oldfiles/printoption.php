<?php
  session_start();
    if(isset($_GET['printer']) && !empty($_GET['printer'])) {
      $_SESSION['user_printer'] = $_GET['printer'];
      if ($_GET['color'] == "Black and White Printing Only"){
        $_SESSION['user_color'] = $_GET['color'];
        ?>
        <script>
          var colorSelection = ["Monochrome/Black and White"];
        </script>
      <?php
    }else{
      ?>
      <script>
        var colorSelection  = ["Monochrome/Black and White", "Color"];
      </script>
    <?php
    }
}
  if(isset($_GET['err']) && !empty($_GET['err'])) {
    ?>
    <script>
      alert("Invalid Page Range!!");
    </script>
    <?php
  }
  echo "1";
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Upload Document</title>
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
    </style>
  </head>

  <body class="fullbleed vertical layout">
    <template is="dom-bind" id="app">
      <paper-header-panel class="flex">
        <paper-toolbar>
          <div><h1> Print's Options <h1></div>
        </paper-toolbar>
        <div class="center">
          <form id="printfile" method="GET" action="processprint.php">
            <table>
              <tr>
                <td><p class="indicator">Copies<p></td>
                <td><input is="iron-input" class="number" allowed-pattern="[0-9]" bind-value={{copies}}></td>
              </tr>
              <tr>
                <td><p class="indicator">Color<p></td>
                <td>
                  <paper-dropdown-menu label="Color">
                    <paper-listbox class="dropdown-content" selected="{{select}}">
                      <template is="dom-repeat" items="[[colors]]" as="color">
                        <paper-item>[[color]]</paper-item>
                      </template>
                    </paper-listbox>
                </paper-dropdown-menu>
                </td>
              </tr>
              <tr>
                <td><p class="indicator">Pages (0=ALL)<p></td>
                <td><input is="iron-input" class="number-medium" allowed-pattern="[-0-9]" value="0" bind-value={{range}}></td>
              </tr>
            </table>
            <input type="hidden" name="copies" id="copies">
            <input type="hidden" name="color" id="color">
            <input type="hidden" name="range" id="range">
          </form>
          <paper-button raised on-click="submitform">P R I N T</paper-button>
        </div>
      </paper-header-panel>
    </template>
  </body>

  <script>
    var app = document.querySelector("#app");
    app.colors = colorSelection;
    app.select = 0;
    app.copies = 1;
    app.submitform = function(event){
      this.$.copies.value = this.copies;
      this.$.color.value = this.select;
      this.$.range.value = this.range;
      this.$.printfile.submit();
    }
  </script>
</html>
