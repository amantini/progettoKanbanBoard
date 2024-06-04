<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login e Registrazione</title>
    <link rel="stylesheet" href="css/styleindex.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div id="button-box">
            <button id="btn-login" onclick="showLoginForm()">Login</button>
            <button id="btn-register" onclick="showRegisterForm()">Register</button>
        </div>
        <?php
        session_start();
        include "config/config.php";

        $msg = "";

        if (isset($_POST["loginUsername"]) && isset($_POST["loginPassword"])) {
            $username = $_POST["loginUsername"];
            $password = $_POST["loginPassword"];

            if ($username === $usernameAdmin && $password === $passwordAdmin) {
                $_SESSION["credenziali"] = $username;
                header("Location: carica.php");
                exit;
            }

            $conn = @mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

            // Se la connessione fallisce, mostra un messaggio di errore
            if (!$conn) {
                $msg = "Username o password non corretti";
            } else {
                $username = mysqli_real_escape_string($conn, $_POST["loginUsername"]);
                $password = mysqli_real_escape_string($conn, $_POST["loginPassword"]);

                $query = "SELECT * FROM utenti WHERE username = '$username' AND password = '$password'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    $_SESSION["credenziali"] = $username;
                    header("Location: pagina.php");
                    exit;
                } else {
                    $msg = "Username o password non corretti";
                }

                mysqli_close($conn);
            }
        }

        if (isset($_POST["registraUsername"])) {
            $conn = @mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

            if (!$conn) {
                $msg = "Errore di connessione al database.";
            } else {
                $username = mysqli_real_escape_string($conn, $_POST["registraUsername"]);
                $nome = mysqli_real_escape_string($conn, $_POST["registraNome"]);
                $cognome = mysqli_real_escape_string($conn, $_POST["registraCognome"]);
                $password = mysqli_real_escape_string($conn, $_POST["registraPassword"]);

                $sql = "SELECT username FROM utenti WHERE username = '$username'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    $msg = "Utente già registrato";
                } else {
                    $sql = "INSERT INTO utenti (username, nome, cognome, password) VALUES ('$username', '$nome', '$cognome', '$password')";
                    if (mysqli_query($conn, $sql)) {
                        $msg = "Utente registrato con successo!";
                    } else {
                        $msg = "Errore nell'inserimento";
                    }
                }
                mysqli_close($conn);
            }
        }
        ?>
        <form id="loginForm" action="" method="post">
            <h2>Login</h2>
            <div class="form-group">
                <input type="text" id="loginUsername" name="loginUsername" placeholder="Username" >
            </div>
            <div class="form-group">
                <input type="password" id="loginPassword" name="loginPassword" placeholder="Password">
            </div>
            <div class="form-group">
                <button class="btn-register-login" type="submit">Login</button>
            </div>
            <?php if ($msg !== "") { ?>
                <p class="error"><?php echo $msg; ?></p>
            <?php }; ?>
        </form>
        <form id="registerForm" class="register-form" action="" method="post">
            <h2>Registrazione</h2>
            <div class="form-group">
                <input type="text" id="registerName" name="registraNome" placeholder="Nome">
            </div>
            <div class="form-group">
                <input type="text" id="registerSurname" name="registraCognome" placeholder="Cogn
