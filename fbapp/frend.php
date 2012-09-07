<?php 
$app_id = "144623399011345";
$canvas_page = "https://apps.facebook.com/showcoderutestapp/";

$message = "Would you like to join me in this great app?";

$requests_url = "https://www.facebook.com/dialog/apprequests?app_id=" 
. $app_id . "&redirect_uri=" . urlencode($canvas_page)
. "&message=" . $message;

if (empty($_REQUEST["request_ids"])) {
    echo("<script> top.location.href='" . $requests_url . "'</script>");
} else {
    echo "Request Ids: ";
    print_r($_REQUEST["request_ids"]);
}
?>

