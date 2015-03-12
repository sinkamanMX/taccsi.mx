<?php
    $url = "https://ws.pushapps.mobi/RemoteAPI/CreateNotification";
    $token = 'APA91bH7pr2nY-vRK1aH5ySz77ZPEWsCCaMTZNQzNl8p3KQWRVAfnQrdIuE_YT8Kep5pNwZ9pcTBpvr3qMr7YUYmuouR9xXHbLdaZuxE2E_vitDwPI4Bk9qzmKXDR_8-ThsYe7SXM-pp9-5lOcTC43xW6KB70mYAEddJYLj29iE2SZvoUOQJBmI';
    $data = array(
                  'SecretToken'                     => '13695fb2-6fd8-4acc-981b-baedaf84a5e1', ## Your app secret token
                  'Message' => 'Tu tacssii va en camino, tablet', ## The message you want to send
                  'Platforms' => array(),## Optional, platforms to send to, if empty will send to all configured platforms, will be overridden if Devices is        specified
                  'Devices' => array(array(
                                           'PushToken' => $token, ## Device push token
                                           'DeviceType' => 1
                                           )) ## Optional, array of devices to send, if empty will send to all registered users, or by Platforms if specified
                  );
     
    $content = json_encode($data);
     
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
     
    $json_response = curl_exec($curl);
     
    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     
    if ( $status != 200 ) {
        die("Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
    }
     
    curl_close($curl);
     
   // echo "Response: " . $json_response . "\n";
//	   var_dump(json_decode($json_response));
	  // echo $x->{'code'};  
$x =json_decode($json_response);
echo $x->Code;   
?>
