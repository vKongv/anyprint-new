<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$polymerAsset = \frontend\assets\PolymerAsset::register($this);
//$this->registerAssetBundle(\frontend\assets\PolymerAsset::className(), \yii\web\View::POS_HEAD);
?>
<!DOCTYPE HTML>
<?php
  if (isset($_GET['err'])){
    $errCode = $_GET['err'];
    if($errCode == "invaliduser"){
      ?>
      <script>
        alert('Incorrect username or password');
      </script>
    <?php
  }else if($errCode == "invalidtoken"){
        ?>
        <script>
          alert('Error in retrieving your account in Google. Please sign in for your Google account.');
        </script>
    <?php
      header("Location: gcp/oAuthRedirect.php?op=offline");
      }
    }

    if (isset($_SESSION['login_uid']) && !empty($_SESSION['login_uid'])){
      if (isset($_SESSION['user_type'])){
        if($_SESSION['user_type'] == "BO"){
          header("Location: businessprofile.php");
        }else if($_SESSION['user_type'] == "NORMAL"){
          header("Location: userprofile.php");
        }else{
        }
      }else{
      }
    }else{
    }
?>
<html>
<head>
   <meta charset="utf-8"/>
   <meta http-equiv="content-type" content="text/html" />
   <meta name ="viewport" content="width=device-width, initial-scale=1" />

   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/paper-input/paper-input.html" />
   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/paper-button/paper-button.html" />
   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/paper-header-panel/paper-header-panel.html" />
   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/paper-toolbar/paper-toolbar.html" />
   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/iron-flex-layout/iron-flex-layout.html">
   <link rel="import" href="<?= $polymerAsset->baseUrl ?>/font-roboto/roboto.html">

   <script src="<?= $polymerAsset->baseUrl ?>/webcomponentsjs/webcomponents-lite.js"></script>
   <style is="custom-style">
     body {
       font-family: Roboto;
     }
     .centered {
       margin: auto;
       margin-left: auto;
       margin-right: auto;
       margin-top: 50px;
       max-width: 500px;
       max-height: 700px;
     }
     paper-button.blue {
       background: #22A7F0;
       color: white;
       margin-top: 10px;
       width: 100%;
     }
   </style>
</head>

 <body>
   <!-- Login form -->
   <template is="dom-bind" id="app">
    <!-- Active Form for Yii model -->
     <?php $form = ActiveForm::begin(['id' => 'formlogin']); ?>
     <?= $form->field($model, 'username')->hiddenInput(['value' => '{{uname}}', 'id' => 'uname'])->label(false) ?>
     <?= $form->field($model, 'password')->hiddenInput(['value' => '{{upassword}}', 'id' => 'uname'])->label(false) ?>
     <?php ActiveForm::end(); ?>

    <!-- Polymer interface for form-->
     <center><a href="index.php"><img src="res/anyprintlogo.png" align="middle" alt="Anyprint's Logo" width="90px" height="72px"></img></a></center>
       <div class="centered">
         <paper-toolbar><h2>Login to your account</h2></paper-toolbar>
         <paper-material elevation="1" style="padding: 30px; border-radius: 5px">
           <paper-input  id= "unamefake" label="Username" required auto-validate error-message="Please type your username" value="{{uname}}" ></paper-input>
           <paper-input id="upasswordfake" label="Password" type="password" required auto-validate error-message="Please type your password" value={{upassword}}></paper-input>
           <paper-button class="blue" name="btnlogin" raised onclick="submitHandler()" value="login">Log in</paper-button>
       </paper-material elevation="1">
       <p>Don't have an account? Register <a href="signup.html">now</a></p>
        </div>
    </template>


 <script>
  var app = document.querySelector('#app');
  var canSubmit = true;
  var submitHandler = function(){
    canSubmit = true;
     //Validate input
     if(app.uname == ""){
       canSubmit = false;
     }
      if(app.upassword == ""){
       canSubmit = false;
     }

      //If all input OK, then submit
     if(canSubmit){
       app.formUName = app.uname;
       app.formUPassword = app.upassword;
       app.$.formlogin.submit();
     }
      else{
        alert("Username or password cannot be blank");
        return;
      }
    }
    </script>
 </body>
 </html>
