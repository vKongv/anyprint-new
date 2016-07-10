<!DOCTYPE HTML>
<html>
<head>
  <title>Print page</title>
  <?php
    include ('temp.php');
    include('config.php');
    $sqlGetPID; // SQL Get printing shop ID
    $dataRetrievePID; //dbconnection
    $pid;
    if(isset($_POST['printnow'])){
      $sqlGetPID = "SELECT P_ID_G FROM printer WHERE PS_ID = " . $_POST['pname'] . " LIMIT 1";
      $dataRetrievePID = mysqli_query($dbcon,$sqlGetPID);
      $pid = mysqli_fetch_row($dataRetrievePID);
      echo "$pid[0]\n";
    }
   ?>

   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
   <script src="http://malsup.github.com/jquery.form.js"></script>
 </head>
<body>
  <form id="submitprint" action="https://www.google.com/cloudprint/submit" method="post" enctype="multipart/form-data">
    <input type ="hidden" name="xsrf" value="<?php echo $xsrf;?>"/>
    <input type="hidden" name="printerid" value="<?php echo $pid[0]?>"/>
    <input type="hidden" name="title" value="Test print"/>
    <input type="hidden" name="contentType"/>
    <textarea rows="15" cols="40" name="ticket" id="submit_ticket"/>{
  "version": "1.0",
  "print": {}
}
  }</textarea>
   <input type="file" name="content" id="submit_content" />
   <input type="hidden" name="jobid"/>
   <input type="submit" id="submit" value="Confirm?">
  </form>

  <script>
  $('#myForm')
    .ajaxForm({
        url : 'https://www.google.com/cloudprint/submit', // or whatever
        dataType : 'json',
        success : function (response) {
            alert("The server says: " + response);
        }
    });
  </script>
</body>
</html>
