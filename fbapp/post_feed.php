<?
  // Remember to copy files from the SDK's src/ directory to a
  // directory in your application on the server, such as php-sdk/
  require_once('fb-sdk/src/facebook.php');

  $config = array(
    'appId' => '144623399011345',
    'secret' => '49869233d732bab715cf27807ccca806',
    );

  $facebook = new Facebook($config);
  $user_id = $facebook->getUser();
?>
<html>
  <head></head>
  <body>

  <?
    if($user_id) 
{

      // We have a user ID, so probably a logged in user.
      // If not, we'll get an exception, which we handle below.
      try {
       $ret_obj = $facebook->api('/me/feed', 'POST',
               array(
                   'link' => 'showcode.ru/events/view/9d58b520',
                   'message' => 'Новый семинар!'));
        echo '<pre>Post ID: ' . $ret_obj['id'] . '</pre>';

      } catch(FacebookApiException $e) {
        // If the user is logged out, you can have a 
        // user ID even though the access token is invalid.
        // In this case, we'll get an exception, so we'll
        // just ask the user to login again here.
        $login_url = $facebook->getLoginUrl( array(
                       'scope' => 'publish_stream,create_event,rsvp_event,user_events,friends_events'
                       )); 
        echo 'Please <a href="' . $login_url . '">login.</a>';
        error_log($e->getType());
        error_log($e->getMessage());
      }   
      // Give the user a logout link 
      echo '<br /><a href="' . $facebook->getLogoutUrl() . '">logout</a>';
    } else {

      // No user, so print a link for the user to login
      // To post to a user's wall, we need publish_stream permission
      // We'll use the current URL as the redirect_uri, so we don't
      // need to specify it here.
      $login_url = $facebook->getLoginUrl( array('scope' => 'publish_stream,create_event,rsvp_event,user_events,friends_events') );
      echo 'Please <a href="' . $login_url . '">login.</a>';

    } 

  ?>      

  </body> 
</html>  
