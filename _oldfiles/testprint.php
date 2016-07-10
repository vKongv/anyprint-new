<!DOCTYPE HTML>
<html>
<head>
  <title>Print page</title>
  <?php
    session_start();
    include("config.php");
    $sqlGetPSName = "SELECT PS_ID, PS_Name FROM printing_shop ORDER BY PS_Name";
    $dataRetrievePSName = mysqli_query($dbcon,$sqlGetPSName);
   ?>

 </head>
 <body>
   <form id = "print" action="sessiontest.php" method="post">
     <table>
       <tr>
         <td> Printing Shop: </td>
         <td> <select name="pname">
           <?php
              $i = 0;
              while($psname = mysqli_fetch_row($dataRetrievePSName)){
                echo "
                  <option value =  $psname[0]> $psname[1] </option>
              ";
            }
           ?> </select>
         </td>
       </tr>
       <tr>
         <td></td>
         <td><input type="submit" value="Print" name="printnow" id="printnow  "></td>
       </tr>
     </table>
 </body>
 </html>
