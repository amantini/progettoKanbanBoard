<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #273D59;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 800px;
            text-align: center;
            background-color: #ebebeb;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #005C53;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        td {
            border-bottom: 1px solid #ddd;
        }
        td[contenteditable="true"] {
            background-color: #f9f9f9;
            cursor: text;
        }
        tr:hover td[contenteditable="true"] {
            background-color: #f1f1f1;
        }
        button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            font-size: 16px;
            background-color: #005C53;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .link-container {
            margin-top: 20px;
        }
        .link-container a {
            color: #005C53;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        .link-container a:hover {
            color: #005C53;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="omino.png" alt="User Image" class="profile-image">
        <?php
            session_start();
            if (!isset($_SESSION["credenziali"])) {
                header("Location: login.php");
                exit;
            }
            $username = $_SESSION["credenziali"];
            $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT username, nome, cognome, password FROM utenti WHERE username = '$username'";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table><tr><th>Nome</th><th>Cognome</th><th>Username</th><th>Password</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td contenteditable='true' id='tdNome'>".$row["nome"]."</td>
                        <td contenteditable='true' id='tdCognome'>".$row["cognome"]."</td>
                        <td contenteditable='true' id='tdUsername'>".$row["username"]."</td>
                        <td contenteditable='true' id='tdPassword'>".$row["password"]."</td>
                        </tr>";
                }
                echo "</table>";
            }
            $conn->close();
        ?>
        <button onclick="invia()">Cambia dati</button>
        <div class="link-container">
            <a href="pagina.php">Vai alla pagina principale</a>
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
        }
    </script>

</body>
</html>
