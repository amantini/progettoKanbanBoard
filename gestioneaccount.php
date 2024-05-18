<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        a:link,
        a:visited {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        a:hover,
        a:active {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        td[contenteditable='true']:hover {
            background-color: #e8f0fe;
            cursor: text;
        }

        td[contenteditable='true']:focus {
            border: 2px solid #4d90fe;
            outline: none;
        }

        input {
            padding: 1em;
            margin-top: 1em;
        }
</style>
</head>
<body>
<?php
    session_start();
    if (!isset($_SESSION["credenziali"])) {
        header("Location: error.php");
        exit;
    }
    $username = $_SESSION["credenziali"];
        $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");
    
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $sql = "SELECT username, nome, cognome, password FROM utenti where username = '$username'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Nome</th><th>Cognome</th><th>Username</th><th>Password</th>";
            while($row = $result->fetch_assoc()) {
              echo "<tr><td contenteditable='true' name='tdNome'>".$row["nome"]."</td><td contenteditable='true' name='tdCognome'>".$row["cognome"]."</td><td contenteditable='true' name='tdUsername'>".$row["username"]."</td><td contenteditable='true' name='tdPassword'>".$row["password"]."</td></tr>";
            }
            echo "</table>";
            echo "</select>";
          }
        $conn->close();
?>

<form method="POST">
    <input type="submit" value="Cambia dati">
</form>
</body>
</html>