<?php
require 'openid.php';
require_once "rewardconfig.php";
$redirectUri = $redirectUri . "/auth_steam.php";

$openid = new LightOpenID($redirectUri);
try {
    if ($openid->mode == 'cancel') {
        echo 'Authorization has been canceled.';
    } elseif ($openid->validate()) {
        $identity = $openid->identity;
        preg_match('/^https?:\/\/steamcommunity\.com\/openid\/id\/(\d+)$/', $identity, $matches);
        $steamID64 = $matches[1];
        setcookie('steamid', $steamID64);
        header("Location: index.php");
    } else {
        try {
            $openid->identity = 'https://steamcommunity.com/openid';
            header('Location: ' . $openid->authUrl());
        } catch (ErrorException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
} catch (ErrorException $e) {
    echo "Error: " . $e->getMessage();
}