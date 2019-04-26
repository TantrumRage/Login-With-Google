<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();

// OAuth 2.0 settings
//
// Go to the Google API Console, open your application's
// credentials page, and copy the client ID, client secret,
// redirect URI, and API key. Then paste them into the
// following code.
$client->setClientId('1009089897352-65f3rcibnb60larj1v7u53aberutlj5u.apps.googleusercontent.com');
$client->setClientSecret('po92d6GqkytyRGgRY3QGrtyY');
$client->setRedirectUri('http://localhost/practice/API/Gmail%20Login/login.php');

$client->addScope('profile');
$client->addScope([Google_Service_PeopleService::USERINFO_EMAIL, Google_Service_PeopleService::USERINFO_PROFILE]);

if (isset($_GET['oauth'])) {
  // Start auth flow by redirecting to Google's auth server
  $auth_url = $client->createAuthUrl();
  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} else if (isset($_GET['code'])) {
  // Receive auth code from Google, exchange it for an access token, and
  // redirect to your base URL
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/practice/API/Gmail%20Login/login.php';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
} else if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
  // You have an access token; use it to call the People API
  $client->setAccessToken($_SESSION['access_token']);
  
  // TODO: Use service object to request People data
} else {
  $redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/practice/API/Gmail%20Login/login.php/?oauth';
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}

  $people_service = new Google_Service_PeopleService($client);

  $user = $people_service->people->get('people/me', array('personFields' => 'names,emailAddresses'));

  $user_email = $user->getEmailAddresses();
  $user_name = $user->getNames();
  
  $newUserEmail = $user_email[0];
  $newUserName = $user_name[0];
  
  echo 'Your name: ';
  print_r($newUserName->getDisplayName());
  
  echo '<br>Your email address: ';
  print_r($newUserEmail->getValue());
  
  echo '<br>';

  

  //echo $user['names'][0]['displayName'];
  
//echo "<br><br>hi " . $user['displayName'];

?>

<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script>
    
  </script>
</head>
<body>
  <a href="logout.php"><button>LOGOUT</button></a> 
</body>
</html>