<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Account</title>
    <link rel="stylesheet" href="css/stylegestioneaccount.css">
</head>

<body>
    <div class="container">
        <img src="img/omino.png" alt="User Image" class="profile-image">
        <?php
        session_start();
        include 'config/config.php';
        
        if (!isset($_SESSION["credenziali"])) {
            header("Location: indice.php");
            exit;
        }
        $username = $_SESSION["credenziali"];
        $conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT username, nome, cognome, password FROM utenti WHERE username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table><tr><th id='thUsername'>Username</th><th>Nome</th><th>Cognome</th><th>Password</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td id='tdUsername'>" . $row["username"] . "</td>
                        <td contenteditable='true' id='tdNome'>" . $row["nome"] . "</td>
                        <td contenteditable='true' id='tdCognome'>" . $row["cognome"] . "</td>
                        <td contenteditable='true' id='tdPassword'>" . $row["password"] . "</td>
                        </tr>";
            }
            echo "</table>";
        }
        $conn->close();
        ?>
        <button onclick="invia()">Cambia dati</button>
        <div class="message" id="messaggioConferma">Cambio dati effettuato</div>
        <div class="link-container">
            <a href="pagina.php">&lt; Torna indietro</a>
        </div>
    </div>

    <script>
        async function invia() {
            var nome = document.getElementById('tdNome').innerText;
            var cognome = document.getElementById('tdCognome').innerText;
            var username = document.getElementById('tdUsername').innerText;
            var password = document.getElementById('tdPassword').innerText;
            const risposta = await fetch('modificaUtente.php', {
                method: 'POST',
                body: JSON.stringify({
                    nome: nome,
                    cognome: cognome,
                    username: username,
                    password: password
                }),
                headers: {
                    'Content-type': 'application/json; charset=UTF-8'
                }
            });
            if (risposta.ok) {
                const message = document.getElementById('messaggioConferma');
                message.classList.add('show');
                setTimeout(() => {
                    message.classList.remove('show');
                }, 3000);
            }
        }
    </script>

</body>

</html>