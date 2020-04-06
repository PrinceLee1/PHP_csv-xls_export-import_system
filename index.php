<?php

//index.php

//Include Configuration File
include('config.php');

$login_button = '';

//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email_address'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}

//This is for check user has login into system by using Google account, if User not login into system then it will execute if block of code and make code for display Login link for Login using Google account.
if(!isset($_SESSION['access_token']))
{
 //Create a URL to obtain user authorization
 $login_button = '<a href="'.$google_client->createAuthUrl().'"><button type="button"  style="float:left;width:300px" class="btn btn-primary">GET STARTED</button></a>';
}

?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>CSV import & Export system</title>
  <meta content='width=device-width, initial-scale=1, maximum-scale=1' name='viewport'/>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  <style>
  .card{
      margin-top:75px
  }
  .login h3{
font-size:50px;
color:teal;
font-family:'Nato';
text-transform:uppercase
  }
  button{
      height:80px;
      font-family:'Nato';
  }
  h4{
transform:translateY(30px)  }
  footer{
      margin-top:60px;
      background:orange;
      height:70px
  }
  .login p{
      font-size:20px;
      color:#32325d;
      font-family:'Nato';

  }
  #myProgress {
  width: 100%;
  background-color: #ddd;
}
.card{
  box-shadow:  0 .5rem 1rem rgba(0,0,0,.15)!important;
  color:#32325d;
  }

#myBar {
  width: 1%;
  height: 30px;
  background-color: #4CAF50;
}
  </style>
 </head>
 <body>
 
  <div class="container">
  <div class="row">
   <br />
   <br />
   <div class="card" >
   <div class="card-body">
   <?php
   if($login_button == '')
   {
   $name = $_SESSION['user_first_name'];
    echo '<div class="col-md-4"><div class="text-center"><img src="'.$_SESSION["user_image"].'" class="img-responsive img-thumbnail" /></div></div>';
    // echo '<div class="col-md-8">';
    echo '<h3 class="text-center"><b>Name :</b> '.$name.' '.$_SESSION['user_last_name'].'</h3>';
    echo '<h3 class="text-center"><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';
    echo '<h3 class="text-center" style="color:blue"><a href="logout.php">Logout</a></h3></div>';
    require_once 'handleCSv.php';
    // echo '</div>';

   }
   else
   {
    echo '
    <div class="row login">
    <div class="col-md-6">
    <img src="csv.png" class="img-responsive" style="height:400px">
    </div>
<div class="col-md-6">
<h3>One click sign in!</h3>
<p>The simple way to save,import and export CSV files.<br> Can export as PDF also.</p>
<p>There are <b>no passwords</b> to remeber<br> and it is <b>totally free</b> to use.</p>
<div align="center">'.$login_button . '</div></div>
    </div>
    
    ';
   }
   ?>
   </div>
   </div>
  </div>
  </div>
  <footer>
 <h4 class="text-center"> Made by Prince Lee</h4>
  </footer>
 </body>
</html>

