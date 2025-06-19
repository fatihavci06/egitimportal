<?php
$client_id = '6PODjEJYRjaWnZ7uXqnT9Q';
$redirect_uri = urlencode('http://localhost/lineup_campus/zoom/callback.php');

$url = "https://zoom.us/oauth/authorize?response_type=code&client_id={$client_id}&redirect_uri={$redirect_uri}";

header("Location: $url");
exit;
