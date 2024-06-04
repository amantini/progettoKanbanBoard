<?php
session_start();
if (!isset($_SESSION["credenziali"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica DB CSV</title>
    <link rel="stylesheet" href="css/stylecarica.css">
    <style>

    </style>
</head>

<body>
    <div class="container">
        <h1>Carica il tuo file CSV</h1>
        <form action="caricaConferma.php" method="post" enctype="multipart/form-data">
            <input type="file" name="nomeFile" required>
            <input type="submit" value="Carica">
        </form>
        <a href="logout.php">LOGOUT</a>
    </div>
</body>

</html>
