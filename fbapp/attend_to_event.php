<?
// Remember to copy files from the SDK's src/ directory to a
// directory in your application on the server, such as php-sdk/
function parse_signed_request($signed_request, $secret) {
      list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

        // decode the data
        $sig = base64_url_decode($encoded_sig);
          $data = json_decode(base64_url_decode($payload), true);

            if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
                    error_log('Unknown algorithm. Expected HMAC-SHA256');
                        return null;
                          }

              // check sig
              $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
                if ($sig !== $expected_sig) {
                        error_log('Bad Signed JSON signature!');
                            return null;
                              }

                  return $data;
}

function base64_url_decode($input) {
      return base64_decode(strtr($input, '-_', '+/'));
}

$me = parse_signed_request($_REQUEST['signed_request'], '49869233d732bab715cf27807ccca806' );
var_dump($me);


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
	
	$event_id = '354890784590741';
//	$event_id = 462227377151252;
$access_token = $facebook->getAccessToken();
var_dump($access_token);
echo "<br>";
var_dump('/'.$event_id . '/invited/' . $user_id);

	// We have a user ID, so probably a logged in user.
	// If not, we'll get an exception, which we handle below.
	try {
       $ret_obj = $facebook->api('/me', 'GET', array('access_token'=>$access_token));
		// Give the user a logout link 
		// Check if user 1 is invited to the event
		$ret_val = $facebook->api('/'.$event_id . '/invited/' . $user_id, 'GET', array('access_token'=>$access_token));
		if (!count($ret_obj['data']))
		{ // not invited
			$ret_val = $facebook->api('/'.$event_id . '/invited/' . $user_id, 'POST', array('access_token'=>$access_token));
			if(count($ret_obj['data'])) {
				printMsg('User successfully invited.');
			}else {
				printMsg('Couldn\'t invite User');
			}

		}
	} catch(FacebookApiException $e) {
		// If the user is logged out, you can have a 
		// user ID even though the access token is invalid.
		// In this case, we'll get an exception, so we'll
		// just ask the user to login again here.
		$login_url = $facebook->getLoginUrl( array(
					'scope' => 'publish_stream,create_event,rsvp_event,user_events,friends_events,publish_actions'
					)); 
		echo 'Please <a href="' . $login_url . '">login.</a>';

		echo ($e->getType());
		echo ($e->getMessage());

	}   
} else {

	// No user, so print a link for the user to login
	// To post to a user's wall, we need publish_stream permission
	// We'll use the current URL as the redirect_uri, so we don't
	// need to specify it here.
	$login_url = $facebook->getLoginUrl( array('scope' => 'publish_stream,create_event,rsvp_event,user_events,friends_events,publish_actions') );
	echo 'Please <a href="' . $login_url . '">login.</a>';

} 

?>      
<a href="index.php">index</a>
</body> 
</html>  
