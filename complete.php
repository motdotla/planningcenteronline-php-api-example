<?php
require_once "OAuth.php";       //oauth library
require_once "common.php";      //common functions and variables

//get request token params from cookie and parse values
$requestToken   = $_COOKIE["requestToken"];
parse_str($requestToken, $request_token_array);
$secret         = $secret;
$key            = $request_token_array["key"];
$token          = $request_token_array["token"];
$token_secret   = $request_token_array["token_secret"];
$oauth_verifier = $_GET['oauth_verifier'];

//create required consumer variables
$test_consumer  = new OAuthConsumer($key, $secret, NULL);
$req_token      = new OAuthConsumer($token, $token_secret, NULL);
$sig_method     = new OAuthSignatureMethod_HMAC_SHA1();
// echo "<p>============================================</p>";
// echo $req_token;
// echo "<p>============================================</p>";

//exchange authenticated request token for access token
$params         = array('oauth_verifier' => $oauth_verifier);
$acc_req        = OAuthRequest::from_consumer_and_token($test_consumer, $req_token, "GET", $oauth_access_token_endpoint, $params);
$acc_req->sign_request($sig_method, $test_consumer, $req_token);
$access_ret     = run_curl($acc_req->to_url(), 'GET');

// //if access token fetch succeeded, we should have oauth_token and oauth_token_secret parse and generate access consumer from values
parse_str($access_ret, $access_token);
// echo "<p>============================================</p>";
// echo print_r($access_token);
// echo "<p>============================================</p>";

$access_consumer = new OAuthConsumer($access_token['oauth_token'], $access_token['oauth_token_secret'], NULL);




// 4. Set Person id for example 1 and example 2
$person_id = "2283";


//*******************************************************************
// EXAMPLE 1. GET Request to pull a person
//*******************************************************************
// build url - gets person with id of 2283
$url = sprintf("http://www.planningcenteronline.com/people/$person_id.xml");

// build and sign request
$request = OAuthRequest::from_consumer_and_token($test_consumer,
  $access_consumer, 
  'GET',
  $url, 
  NULL);
$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(),
  $test_consumer, 
  $access_consumer
);

// make request
$response = run_curl($request, 'GET');
echo "<p>============================================</p>";
echo $request;
echo "<p>============================================</p>";
echo htmlentities($response);
echo "<p>============================================</p>";



//*******************************************************************
// EXAMPLE 2. PUT Request to put a person
//*******************************************************************
// build url - gets person with id of 2283
$url = sprintf("http://www.planningcenteronline.com/people/$person_id.xml");

//build and sign request
$request = OAuthRequest::from_consumer_and_token($test_consumer, 
 $access_consumer, 
 'PUT',
 $url, 
 array());
$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(),
 $test_consumer, 
 $access_consumer
);

//define request headers
$headers = array("Accept: application/xml");
$headers[] = $request->to_header();
$headers[] = "Content-type: application/xml";

// $content = json_encode($body);
// set content
$content = '<person><first-name>Jeff</first-name><last-name>Berg</last-name></person>';

// Make put request
$response = run_curl($url, 'PUT', $headers, $content);

//if debug mode, dump signatures & headers 
echo "<p>============================================</p>";
if ($debug){
    $debug_out = array('Access token' => $access_token,
                       'PUT URL'      => $url,
                       'PUT headers'  => $headers,
                       'PUT content'  => $content,
                       'PUT response' => $response);
    
    print_r($debug_out);
}
echo "<p>============================================</p>";
?>