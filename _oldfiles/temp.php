<?php
//ABLE TO SEND SMS!!! YEAH!!!!!!!!!
session_start();
require __DIR__.'/vendor/autoload.php';
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

  if(isset($_GET['content']) && !empty($_GET['content'])) {
    if(isset($_GET['number']) && !empty($_GET['number'])) {
      $messagecontent = $_GET['content'];
      $number = $_GET['number'];
      // Create a client with a base URI
      $client = new Client([
          // Base URI is used with relative requests
          'base_uri' => 'https://api.pushbullet.com',
          // You can set any number of default request options.
          'timeout'  => 10.0,
      ]);

      $header = array(
        'Access-Token' => 'o.FqMTx5y80HyK8GxSWZXSRTC5ZgTDxZha'
      );

      $jsonBody = array(
        'push' => array(
          'conversation_iden' => $number,
          'message' => $messagecontent,
          'package_name' => 'com.pushbullet.android',
          'source_user_iden' => 'ujxLcTi4vRI',
          'target_device_iden' => 'ujxLcTi4vRIsjAiVsKnSTs',
          'type' => 'messaging_extension_reply'
        ),
        'type' => 'push'
      );

      $response =  $client->request('POST', '/v2/ephemerals', [
                                     'verify' => false,
                                       'headers' => $header,
                                       'json' => $jsonBody
                                  ]);
      //Get the body of the response, it is a JSON file.
      $json = $response->getBody();
      $decoded = json_decode($json,true);
      print($json);
    }else{
      echo "No number set";
    }
  }else{
    echo "No content set";
  }
?>
