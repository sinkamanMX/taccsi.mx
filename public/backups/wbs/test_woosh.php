<?php
 
    define('PW_AUTH', 'H0oD8pSBC5blOOlgTrNz08QVsQI2m9Tf9f2dJD6ATmqFnR8s20h380BtAFb5Jah3iuvb9n0KKaDbMYZOY30O');
    define('PW_APPLICATION', '9BEA9-0A353');
 
    function doPostRequest($url, $data, $optional_headers = null) {
        $params = array(
            'http' => array(
                'method' => 'POST',
                'content' => $data
            ));
        if ($optional_headers !== null)
            $params['http']['header'] = $optional_headers;
 
        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);
        if (!$fp)
            throw new Exception("Problem with $url, $php_errmsg");
 
        $response = @stream_get_contents($fp);
        if ($response === false)
            return false;
        return $response;
    }
 
    function pwCall( $action, $data = array() ) {
        $url = 'https://cp.pushwoosh.com/json/1.3/' . $action;
        $json = json_encode( array( 'request' => $data ) );
        print_r($json);
        $res = doPostRequest( $url, $json, 'Content-Type: application/json' );
        print_r( @json_decode( $res, true ) );
    }
 
    pwCall( 'createMessage', array(
        'application' => PW_APPLICATION,
        'auth' => PW_AUTH,
        'notifications' => array(
                    array(
                        'send_date' => 'now',
                        'content' => 'prueba web',
                        'ios_badges' => 3,
                        'data' => array( 'custom' => 'json data' ),
                        'link' => 'http://pushwoosh.com/'
                    )
                )
            )
        );
?>
