<?
require_once('fb-sdk/src/facebook.php');
    $app_id = "144623399011345";
    $app_secret = "49869233d732bab715cf27807ccca806";
    $redirect_uri = "https://showcode.ru/fbapp/";

    $config = array();
    $config["appId"] = $app_id;
    $config["secret"] = $app_secret;
    $config["fileUpload"] = true;
    // optional

    $facebook = new Facebook($config);
    $user = $facebook -> getUser();
    if ($user) {
        $logoutUrl = $facebook -> getLogoutUrl();
        $user_profile = $facebook -> api('/me');
        $signed_request = $facebook -> getSignedRequest();

        list($encoded_sig, $payload) = explode('.', $_REQUEST["signed_request"], 2);
        $signed_request = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        $access_token = null;
        if (!empty($_SESSION['access_token'])) {
            $access_token = $_SESSION['access_token'];
        } else if (!empty($signed_request['oauth_token'])) {
            $access_token = $signed_request['oauth_token'];
        }

        if ($access_token != null) {
            $id = $signed_request["user_id"];
            $authorized_code = $_GET["code"];
            $oauth_token = $signed_request["oauth_token"];
            $like_status = $signed_request["page"]["liked"];
            if ($like_status) {
                echo "hello";
            } else {
                echo "Like this page";
            }
        } else if (!empty($_GET["error"])) {
            echo "user hasn't authorized your app";
        } else if (!empty($_GET["code"])) {
            $authorized_code = $_GET["code"];
            $authenticate_url = "https://graph.facebook.com/oauth/access_token?client_id=" . $app_id . "&redirect_uri=" . $redirect_uri . "&client_secret=" . $app_secret . "&code=" . $authorized_code . "";
            $access_token = $facebook -> getAccessToken();
            $_SESSION['access_token'] = $access_token;
die('asdf');
            header('Location: https://www.facebook.com/ShowCodeRu/app_144623399011345');
        }
    } else {
die('asdf1');
        $loginUrl = $facebook -> getLoginUrl(array('scope' => 'publish_stream email user_photos', 'redirect_uri' => $redirect_uri));
        echo("<script>top.location.href='" . $loginUrl . "' </script>");
   }
    ?>
