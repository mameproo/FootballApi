<?php
function callApi($endpoint, $params = []) {
    $apiKey = '5da7381514ecf4e93ee31cbfa6fb9c0b'; 
    $baseUrl = 'https://v3.football.api-sports.io';

    $url = $baseUrl . $endpoint . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "x-apisports-key: $apiKey"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
