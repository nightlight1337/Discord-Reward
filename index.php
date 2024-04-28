<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reward</title>
    <style>
        * {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        .auth-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            text-decoration: none;
            color: #fff;
            background-color: #333;
            border-radius: 5px;
            margin: 10px;
        }

        .auth-button.steam {
            background-color: #1b2838;
        }

        .auth-button.discord {
            background-color: #7289da;
        }
    </style>
</head>

<body>
    <h1>Hello! To receive the award, log in.</h1>

    <div>
        <?php
        if (!isset($_COOKIE["steamid"])) {
            echo ('<a href="auth_steam.php" class="auth-button steam">Sign in via Steam</a>');
        }
        ;
        ?>
        <?php
        if (!isset($_COOKIE["discordid"])) {
            echo ('<a href="auth_discord.php" class="auth-button discord">Sign in via Discord</a>');
        }
        ;
        ?>


    </div>
    <?php
    if (isset($_COOKIE["discordid"]) && isset($_COOKIE["steamid"])) {
        header('Location: final_page.php');
    }
    ;
    ?>

</body>

</html>