<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reward</title>
    <style>
        * {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
    </style>
</head>

<body>
    <?php
    if (isset($_COOKIE["discordid"]) && isset($_COOKIE["steamid"])) {
        require_once "rewardconfig.php";

        $discordId = strval($_COOKIE['discordid']) ?? null;
        $steamId = strval($_COOKIE['steamid']) ?? null;

        if (!$discordId || !$steamId) {
            die(json_encode(['error' => 'Failed to retrieve the required data.']));
        }

        function discordGet($endpoint, $botToken)
        {
            $url = "https://discord.com/api/v10" . $endpoint;
            $headers = [
                "Authorization: Bot $botToken",
                "Content-Type: application/json",
            ];
            $options = [
                'http' => [
                    'method' => 'GET',
                    'header' => implode("\r\n", $headers),
                ],
            ];
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            if ($result === false) {
                die("DiscordAPI request error");
            }

            return json_decode($result, true);
        }

        $memberEndpoint = "/guilds/$Discord_guildId/members/$discordId";
        $member = discordGet($memberEndpoint, $Discord_botToken);

        $isOnServer = false;
        $isBooster = false;

        if (isset($member['user'])) {
            $isOnServer = true;

            if (isset($member['premium_since']) && $member['premium_since'] !== null) {
                $isBooster = true;
            }
        }

        $sqlCheck = "SELECT * FROM reward WHERE steamid = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $steamId);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            if ($isOnServer) {
                $sqlUpdate = "UPDATE reward SET is_booster = ? WHERE steamid = ?";
                $isBoosterValue = $isBooster ? 1 : 0;
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("is", $isBoosterValue, $steamId);
                $stmtUpdate->execute();
                $stmtUpdate->close();
            }
        } else {
            if ($isOnServer) {
                $sqlInsert = "INSERT INTO reward (discordid, steamid, is_booster) VALUES (?, ?, ?)";
                $isBoosterValue = $isBooster ? 1 : 0;
                $stmtInsert = $conn->prepare($sqlInsert);
                $stmtInsert->bind_param("ssi", $discordId, $steamId, $isBoosterValue);
                $stmtInsert->execute();
                $stmtInsert->close();
            }
        }

        $conn->close();

        echo ("<h1>Bonus granted, thank you for being with us!</h1>");
    }
    ;
    ?>
</body>

</html>