<?php

function envia_push_dev_taccista($mensaje,$device,$so){
    $res = false;
    define('APPKEY','nlum2ZrFQzuNmejH_fyZGA'); // Your App Key
    define('PUSHSECRET', 'yDVzQnmPSyGd_nuI7Cf-_w'); // Your Master Secret
    define('PUSHURL', 'https://go.urbanairship.com/api/push/');

   $iosspecific = array();
   $iosspecific['badge'] = "+1";
   $iosspecific['sound'] = "1@qwerty@123";

   $notification = array();
   $notification['alert'] = $mensaje;
   
   $platform = array();
   if ($so == 'I'){
     $notification['ios'] = $iosspecific;
     array_push($platform, "ios");
     $token = array();
     $token ['device_token'] = $device;
   } else {
     array_push($platform, "android");
     $token = array();
     $token ['apid'] = $device;
   }
   $push = array("audience"=>$token, "notification"=>$notification, "device_types"=>$platform);
   //$json = json_encode_noescape_slashes_unicode ($push);
   $json = json_encode($push);
   $payload = $json;
   //echo "Envio: " . $payload . "\n";
   $session = curl_init(PUSHURL);
     curl_setopt($session, CURLOPT_USERPWD, APPKEY . ':' . PUSHSECRET);
     curl_setopt($session, CURLOPT_POST, True);
     curl_setopt($session, CURLOPT_POSTFIELDS, $json);
     curl_setopt($session, CURLOPT_HEADER, False);
     curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
     curl_setopt($session, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Accept: application/vnd.urbanairship+json; version=3;'));
     $content = curl_exec($session);
     //echo "Response: " . $content . "\n";
     // Check if any error occured
     $response = curl_getinfo($session);
     if($response['http_code'] != 202) {
       $result = "Fallo: " . $response['http_code'];
     } else {
       $result = "OK";
       $res = true;
     }
     curl_close($session);
     echo $result;
     echo $content;
     echo $payload;
     //log_push($device,$payload,$content,$result);
     return $res;
   }

$mensaje = 'Prueba';
$so = 'I';
$device = 'cccf0a6fe883177cb5b0bdfdb3ba10c71ae3425dfefd40a67d63d4a0616ab2d8';

envia_push_dev_taccista($mensaje,$device,$so);

?>
