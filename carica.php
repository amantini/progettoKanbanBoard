<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carica DB CSV</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION["credenziali"])) {
        header("Location: indice.php");
        exit;
    }
    ?>
    <form action="caricaConferma.php" method="post" enctype="multipart/form-data">
        <input type="file" name="nomeFile">
        <input type="submit">
    </form>
</body>

</html>