<?php

//config.php

//Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('718246433673-o7l2pjm6lbe0stg08mb9157kmu5br2rq.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('TfiaWZbpDRKZLjFjISLvnS8_');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/single-page-system/index.php');

//
$google_client->addScope('email');

$google_client->addScope('profile');

//start session on web page
session_start();

?>
