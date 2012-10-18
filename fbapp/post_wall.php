<?php 

$app_id = "144623399011345";
$canvas_page = "https://apps.facebook.com/showcoderutestapp/";
         $message = "ShowCodeRu on Facebook.com are cool!";

         $feed_url = "https://www.facebook.com/dialog/feed?app_id=" 
                . $app_id . "&redirect_uri=" . urlencode($canvas_page)
                . "&message=" . $message;

         if (empty($_REQUEST["post_id"])) {
            echo("<script> top.location.href='" . $feed_url . "'</script>");
         } else {
            echo ("Feed Post Id: " . $_REQUEST["post_id"]);
         }
?>

