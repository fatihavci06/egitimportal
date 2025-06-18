<?php
// zoom_auth.php

$clientId = "TMjUAkYSR6BH_Ho7RUVw"; // Buraya kendi Client ID'nizi yazın
$redirectUri = "http://localhost/lineup_campus/zoom_callback.php";
$scope = "meeting:write user:read";

$authorizeUrl = "https://zoom.us/oauth/authorize?" . http_build_query([
    "response_type" => "code",
    "client_id" => $clientId,
    "redirect_uri" => $redirectUri,
    "scope" => $scope,
]);

header("Location: $authorizeUrl");
exit;
