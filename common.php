<?php
// 1. set your planning center online key for your registered application
$key = 'put your secret here after registering your application on planning center';
// 2. set your planning center online secret for your registered application
$secret = 'put your secret here after registering your application on planning center';
// 3. set your callback_url
$callback_url = "http://your-client-application-url.com/complete.php";

$debug = true;
$request_token_endpoint = "http://www.planningcenteronline.com/oauth/request_token";
$authorize_endpoint = "http://www.planningcenteronline.com/oauth/authorize";
$oauth_access_token_endpoint = "http://www.planningcenteronline.com/oauth/access_token";

/***************************************************************************
 * Function: Run CURL
 * Description: Executes a CURL request
 * Parameters: url (string) - URL to make request to
 *             method (string) - HTTP transfer method
 *             headers - HTTP transfer headers
 *             postvals - post values
 **************************************************************************/
function run_curl($url, $method = 'GET', $headers = null, $postvals = null){
    $ch = curl_init($url);
    
    if ($method == 'GET'){
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    } else {
        $options = array(
            CURLOPT_HEADER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_VERBOSE => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postvals,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 3
        );
        curl_setopt_array($ch, $options);
    }
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>
