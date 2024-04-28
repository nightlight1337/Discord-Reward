<?php
require_once "rewardconfig.php";
$redirectUri = $redirectUri . "/auth_discord.php";

if (!isset($_GET['code'])) {
    $authUrl = "https://discord.com/oauth2/authorize?client_id={$Discord_clientId}&response_type=code&redirect_uri={$redirectUri}&scope=guilds+guilds.join+identify";
    header("Location: $authUrl");
    exit;
} else {
    $code = $_GET['code'];
    $postFields = [
        'client_id' => $Discord_clientId,
        'client_secret' => $Discord_clientSecret,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://discord.com/api/oauth2/token');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $response = json_decode($response, true);

    if (isset($response['access_token'])) {
        $accessToken = $response['access_token'];

        $ch = curl_init('https://discord.com/api/users/@me');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer $accessToken"]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $userResponse = curl_exec($ch);
        $user = json_decode($userResponse, true);

        if ($user) {
            $memberID = $user['id'];
            setcookie('discordid', $memberID);
        }
    }
    header("Location: index.php");

}