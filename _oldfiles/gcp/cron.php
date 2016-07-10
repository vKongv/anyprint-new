<?php

// To add printers to your account follow the following link
// https://support.google.com/cloudprint/answer/1686197
/**
 * PHP implementation of Google Cloud Print
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

include '../config.php';
require_once 'Config.php';
require_once 'GoogleCloudPrint.php';

session_start();
// Create object
$gcp = new GoogleCloudPrint();

// Replace token you got in offlineToken.php
$refreshTokenConfig['refresh_token'] = '1/VJYcjvBzkOmyvtPPfe7MUFOkV8YXJNB8nhJoJtxBcK8';

$token = $gcp->getAccessTokenByRefreshToken($urlconfig['refreshtoken_url'],http_build_query($refreshTokenConfig));

$gcp->setAuthToken($token->access_token);

$printers = $gcp->getPrinters();
$uid = $_SESSION['login_uid'];
$sqlcmd = "SELECT P_ID_G FROM user,printer,printing_shop WHERE user.U_ID = $uid AND user.U_ID = printing_shop.U_ID AND printing_shop.PS_ID = printer.PS_ID;";
$dataRetrieve = mysqli_query($dbcon,$sqlcmd);

while($row = mysqli_fetch_row($dataRetrieve)){
	for($i=0; $i<count($printers);$i++){
		if($printers[$i]['id'] == $row[0]){
			$printers[$i]['added'] = 'disabled';
		}
	}
}

print json_encode($printers);

?>
