<?php


/******** Google Login ********/
ini_set('include_path', './lib/google-api-php-client/src');
require_once 'Google/autoload.php';
//Google API PHP Library includes
require_once 'Google/Client.php';
require_once 'Google/Service/Oauth2.php';


$url = $_SERVER['SERVER_NAME'];

// Fill CLIENT ID, CLIENT SECRET ID, REDIRECT URI from Google Developer Console
if(strcmp($url,'localhost')==0){
    $client_id = 'XXXXXX.apps.googleusercontent.com';
    $client_secret = 'XXXXXX';
    $redirect_uri = 'http://localhost/biocaddie-ui/login.php';
}elseif(strcmp($url,'datamed.biocaddie.org')==0){
    $client_id = 'XXXXXX.apps.googleusercontent.com';
    $client_secret = 'XXXXXX';
    $redirect_uri = 'http://datamed.biocaddie.org/login.php';
}elseif(strcmp($url,'datamed.org')==0){
    $client_id = 'XXXXXX.apps.googleusercontent.com';
    $client_secret = 'XXXXXX';
    $redirect_uri = 'https://datamed.org/login.php';
}elseif(strcmp($url,'datamedbeta.biocaddie.org')==0){
    $client_id = 'XXXXXX.apps.googleusercontent.com';
    $client_secret = 'XXXXXX';
    $redirect_uri = 'http://datamedbeta.biocaddie.org/login.php';
}

//Create Client Request to access Google API
$client = new Google_Client();
$client->setApplicationName("Google OAuth Login");
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/userinfo.email");


//Send Client Request
$objOAuthService = new Google_Service_Oauth2($client);



//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    //header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

//Set Access Token to make Request
if (isset($_SESSION['access_token'])) {
    $client->setAccessToken($_SESSION['access_token']);
}

//Get User Data from Google Plus
//If New, Insert to Database
if ($client->getAccessToken()) {

    if($client->isAccessTokenExpired()) {
        $authUrl = $client->createAuthUrl();
        header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    }

    $userData = $objOAuthService->userinfo->get();
    if(!empty($userData)) {
        $objDBController = new DBController();
        $google_user=new GoogleUser();
        $google_user->setDbconn($objDBController->getConn());

        $existing_member = $google_user->getUserByOAuthId($userData->id);
        if(empty($existing_member)) {
            $google_user->insertOAuthUser($userData);
        }
    }
    $_SESSION['access_token'] = $client->getAccessToken();

} else {
    $authUrl = $client->createAuthUrl();
}

?>
