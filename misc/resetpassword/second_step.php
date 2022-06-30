<?php 
    session_start();
    $username = $_SESSION['pw_reset_username'];
    $usermail= $_SESSION['pw_reset_usermail'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sqowey - Passwort</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div id="themeButton">
        <button id="themeToggleButton" onclick="toggleTheme()">Darkmode/Lightmode</button>
    </div>


    <!-- The Container in which the Error output is pasted -->
    <div id="errorOutputContainer">
    </div>

    <!-- container -->
    <div id="container">
        <h1>Passwort zurücksetzen</h1>
        <!-- The form that gets sent to the server -->
        <form id="pw_resetform_two" action="./pwreset.php" method="POST">
            <input class="disabled_form_field" type="text" name="username" placeholder="Nutzername*" id="username" value="<?=$username?>" disabled>
            <input class="disabled_form_field" type="text" name="mail" placeholder="E-Mail*" id="mail" value="<?=$usermail?>" disabled>
            <input type="text" name="code" placeholder="Verifikationscode*" id="code" required>
            <p>Falls E-Mail und nutzername übereinstimmen, wird dir in Kürze eine Mail mit Code geschickt, den du nach Empfang hier eingeben musst</p>
            <input type="submit" id="submit" value="Code Absenden"> <br>
            <a href="./index.html" id="back">Zurück zur Nutzerdateneingabe</a>
        </form>

    </div>
    <script src="themes.js"></script>
    <script src="message_script.js"></script>
</body>

</html>