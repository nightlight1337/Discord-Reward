<?php

$Discord_guildId = "123234312314243349";
$Discord_clientId = "123234323423422344272";
$Discord_clientSecret = "Tu6in2344234234NfdWM2344KKv3Mqn";
$Discord_botToken = "MTIz234234TU5NDc234234.Zav4FXlCdqu234234GgsdgCj23423VS234o";

$redirectUri = "http://localhost/reward/";

$MySQL_hostname = "127.0.0.1";
$MySQL_username = "root";
$MySQL_password = "";
$MySQL_database = "reward";
$MySQL_port = 3306;

$conn = new mysqli($MySQL_hostname, $MySQL_username, $MySQL_password, $MySQL_database, $MySQL_port);

if ($conn->connect_error) {
    die("Database connection error: " . $conn->connect_error);
}

$createTableQuery = "
CREATE TABLE IF NOT EXISTS reward (
    discordid TEXT NOT NULL,
    steamid TEXT NOT NULL,
    is_booster TINYINT(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
";

if ($conn->query($createTableQuery) === false) {
    die("Table creation error: " . $conn->error);
}