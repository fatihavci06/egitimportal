<?php
$client_id = '6zXzyMU2SVOdy5ExzSr8ug';
$redirect_uri = urlencode('http://localhost/lineup_campus/zoom/callback.php');

$url = "https://zoom.us/oauth/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}";

header("Location: $url");
exit;
