<?php
function getSpotifyToken() {
    $clientID = "Your Client ID";  
    $clientSecret = "Your Client Secret";  

    $authHeader = base64_encode($clientID . ':' . $clientSecret);
    $url = "https://accounts.spotify.com/api/token";
    $headers = [
        "Authorization: Basic $authHeader",
        "Content-Type: application/x-www-form-urlencoded"
    ];
    $data = "grant_type=client_credentials";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        echo 'Curl error: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }
    curl_close($ch);

    $responseData = json_decode($response, true);
    if (isset($responseData['error'])) {
        echo 'Error: ' . $responseData['error'] . ' - ' . $responseData['error_description'];
        return null;
    }

    return $responseData['access_token'] ?? null;
}
?>