<?php
    // Start the PHP_session
    session_start();

	// If the user is not logged in redirect to the index-page
    // Also if the session variable id is unset
	if (!isset($_SESSION['name']) || !isset($_SESSION['loggedin']) || !isset($_SESSION['id'])){
		header('Location: ../index.html');
		exit;
	}

    // Variables with the login-credentials
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'accounts';
    
    // Try to Connect with credentials
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    
    // Prepare the SQL
    if ($stmt = $con->prepare('SELECT id, email, password, phone, avatar FROM accounts WHERE username = ?')) {

        // Bind parameters (s = string, i = int, b = blob, etc)
        $stmt->bind_param('s', $_SESSION['name']);
        $stmt->execute();

        // Store the result so we can check if the account exists in the database.
        $stmt->store_result();
        $stmt->bind_result($id, $mail, $password, $phone, $avatar);
        $stmt->fetch();
        $_SESSION['id'] = $id;
        $_SESSION['mail'] = $mail;
        $_SESSION['password'] = $password;
        $_SESSION['phone'] = $phone;
        $_SESSION['avatar'] = $avatar;
        $name = $_SESSION['name'];

        // close the statement
        $stmt->close();
        
            // Prepare the SQL
            if ($stmt = $con->prepare('SELECT privacy_statistics, privacy_enhance, privacy_ads, language FROM settings WHERE user_id = ?')) {

                // Bind parameters (s = string, i = int, b = blob, etc)
                $stmt->bind_param('s', $id);
                $stmt->execute();

                // Store the result to check if the account exists in the database.
                $stmt->store_result();
                $stmt->bind_result($w1, $w2, $w3, $w4);
                $stmt->fetch();

                // Check if any of the variables is unset
                if (!isset($w1) || !isset($w2) || !isset($w3) || !isset($w4)) {

                    // Prepare the SQL
                    if ($stmt = $con->prepare('INSERT INTO accounts (user_id, privacy_statistics, privacy_enhance, privacy_ads, language) VALUES (?, ?, ?, ?, ?)')) {

                        // Bind parameters (s = string, i = int, b = blob, etc)
                        $stmt->bind_param('sssss', $_SESSION['name'], '1', '1', '1', 'en');
                        $stmt->execute();

                        // close the statement
                        $stmt->close();

                        // Reload the page
                        header('Location: settings.php');
                        exit;

                    } else {

                        // Log the error
                        exit("Error-aaaa".mysqli_error($con));
                    }

                } else {

                    // close the statement
                    $stmt->close();
                }

                
                // Check all variables
                // Session var used here: privacy_all
                // If they are true then set the session variable to 1
                // If they are false then set the session variable to 0
                // If they are mixed then set the session variable to 2
                if ($w1 == '1' && $w2 == '1' && $w3 == '1') {
                    $_SESSION['privacy_all'] = '1';
                } elseif ($w1 == '0' && $w2 == '0' && $w3 == '0') {
                    $_SESSION['privacy_all'] = '0';
                } else {
                    $_SESSION['privacy_all'] = '2';
                }

                // Give the local variable privacy all the associated values
                // If Session: 1 then local: <i class="fas fa-check-circle"></i>
                // If Session: 2 then local: <i class="fas far-circle"></i>
                // If Session: 0 then local: <i class="fas fa-times-circle"></i>
                if ($_SESSION['privacy_all'] == '1') {
                    $privacy_all = '<i onclick=\'privacy("all_switch");\' class="fas fa-check-circle"></i>';
                } elseif ($_SESSION['privacy_all'] == '2') {
                    $privacy_all = '<i onclick=\'privacy("all_switch");\' class="far fa-circle"></i>';
                } else {
                    $privacy_all = '<i onclick=\'privacy("all_switch");\' class="fas fa-times-circle"></i>';
                }



                // Check every variable if it is true or false
                // If it is true, set the associated session-variable to '<i class="fas fa-check-circle"></i>'
                // If it is false, set the associated session-variable to '<i class="fas fa-times-circle"></i>'
                if ($w1 == 1) {
                    $_SESSION['privacy_statistics'] = '<i onclick=\'privacy("stats_switch");\' class="fas fa-check-circle"></i>';
                } else {
                    $_SESSION['privacy_statistics'] = '<i onclick=\'privacy("stats_switch");\' class="fas fa-times-circle"></i>';
                }
                if($w2 == 1) {
                    $_SESSION['privacy_enhance'] = '<i onclick=\'privacy("enhance_switch");\' class="fas fa-check-circle"></i>';
                } else {
                    $_SESSION['privacy_enhance'] = '<i onclick=\'privacy("enhance_switch");\' class="fas fa-times-circle"></i>';
                }
                if($w3 == 1) {
                    $_SESSION['privacy_ads'] = '<i onclick=\'privacy("ads_switch");\' class="fas fa-check-circle"></i>';
                } else {
                    $_SESSION['privacy_ads'] = '<i onclick=\'privacy("ads_switch");\' class="fas fa-times-circle"></i>';
                }
                 
                // Copy all session variables to the local variables
                $privacy_statistics = $_SESSION['privacy_statistics'];
                $privacy_enhance = $_SESSION['privacy_enhance'];
                $privacy_ads = $_SESSION['privacy_ads'];

                
                // Set the language to the session-variable
                $language = $w4;

                // Set $lang_de, $lang_en, $lang_fr and $lang_it
                if($language == "de"){
                    $lang_de = '<i class="fas fa-circle"></i>';
                } else {
                    $lang_de = '<i class="far fa-circle"></i>';
                }
                if($language == "en"){
                    $lang_en = '<i class="fas fa-circle"></i>';
                } else {
                    $lang_en = '<i class="far fa-circle"></i>';
                }
                if($language == "fr"){
                    $lang_fr = '<i class="fas fa-circle"></i>';
                } else {
                    $lang_fr = '<i class="far fa-circle"></i>';
                }
                if($language == "it"){
                    $lang_it = '<i class="fas fa-circle"></i>';
                } else {
                    $lang_it = '<i class="far fa-circle"></i>';
                }



                
            }
            else{
                // Log the error
                exit("Error-".mysqli_error($con));

                // Display an error.
                header('Location: settings.php?c=Fehler mit<br>der Datenbank!');
                exit();
            }
    }

    // Close the Database connection
    mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="settings_style.css">
    <script src="https://kit.fontawesome.com/b5c383da68.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div id="name" class="user">
            <div class="icon">
                <img src="../<?=$avatar?>">
            </div>
            <div id="name_field" class="name">
                <?= $name ?>
            </div>
            <div id="email_field" class="email">
                <?= $mail ?>
            </div>
        </div>
        <div class="tabs">
            <ul>
                <a href="#account">
                    <li>
                        <i class="fa-solid fa-user-gear"></i> Account
                    </li>
                </a>
                <a href="#security">
                    <li>
                        <i class="fa-solid fa-shield-halved"></i> Sicherheit
                    </li>
                </a>
                <a href="#links">
                    <li>
                        <i class="fa-solid fa-link"></i> Links
                    </li>
                </a>
            </ul>

        </div>
        <div class="settings">
            <div id="account">
                <h1>Account</h1>
                <div id="settings_details">
                    <h3>Email</h3>
                    <p>
                        muster.mail@muster-mail.de
                    </p>
                    <h3>Benutzername</h3>
                    <p>
                        muster
                    </p>
                    <h3>Telefonnummer</h3>
                    <p>
                        0123456789
                    </p>
                </div>
            </div>
            <div id="security">
                <h1>Sicherheit</h1>
                <div id="settings_details">
                    <h3>Passwort</h3>
                    <button class="warning_button">Passwort ändern</button>
                    <h3>Account löschen</h3>
                    <p>Wenn du deinen Account nur Deaktivierst, kannst du ihn jederzeit wieder Aktivieren!</p>
                    <button class="warning_button">Passwort ändern</button>
                    <button class="warning_button">Passwort ändern</button>
                </div>
                <div id="links">
                    <h1>Links</h1>
                </div>
            </div>
        </div>
    </div>

    <script src="./settings_script.js"></script>

</body>

</html>