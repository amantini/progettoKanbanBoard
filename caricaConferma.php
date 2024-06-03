<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserimento dati CSV in Database</title>
</head>

<body>
    <?php

    session_start();

    $conn1 = mysqli_connect("localhost", "root", "");

    $createDatabase = "CREATE DATABASE IF NOT EXISTS 5i1_brugnoniamantini CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    mysqli_query($conn1, $createDatabase);
    mysqli_close($conn1);

    $conn = mysqli_connect("localhost", "root", "", "5i1_brugnoniamantini");

    $createTableTask = "
    CREATE TABLE IF NOT EXISTS task (
        id INT PRIMARY KEY,
        descrizione VARCHAR(100)
    )";
    mysqli_query($conn, $createTableTask);

    $createTableStati = "
    CREATE TABLE IF NOT EXISTS stati (
        stato INT(11) PRIMARY KEY,
        descrizione VARCHAR(50)
    )";
    mysqli_query($conn, $createTableStati);

    $createTableUtenti = "
    CREATE TABLE IF NOT EXISTS utenti (
        username VARCHAR(16) PRIMARY KEY,
        nome VARCHAR(50),
        cognome VARCHAR(50),
        password VARCHAR(16)
    )";
    mysqli_query($conn, $createTableUtenti);

    $createTableModifiche = "
    CREATE TABLE IF NOT EXISTS modifiche (
        id INT AUTO_INCREMENT PRIMARY KEY,
        data DATE,
        ora TIME,
        descrizione VARCHAR(255),
        fk_utente VARCHAR(16),
        fk_stato INT(11),
        fk_task INT,
        FOREIGN KEY (fk_utente) REFERENCES utenti(username) on update cascade on delete cascade,
        FOREIGN KEY (fk_stato) REFERENCES stati(stato) on update cascade on delete cascade,
        FOREIGN KEY (fk_task) REFERENCES task(id) on update cascade on delete cascade
    )";
    mysqli_query($conn, $createTableModifiche);

    if ($_FILES["nomeFile"]["error"] == 0) {
        $contenuto = file($_FILES["nomeFile"]["tmp_name"], FILE_IGNORE_NEW_LINES);
        $sqlDelete1 = "DELETE FROM utenti";
        $sqlDelete2 = "DELETE FROM task";
        mysqli_query($conn, $sqlDelete1);
        mysqli_query($conn, $sqlDelete2);
        foreach ($contenuto as $riga) {
            $r = explode(',', $riga);
            if ($r[0] == "M") {
                $sql1 = "INSERT INTO modifiche (data, ora, descrizione, fk_utente, fk_stato, fk_task) VALUES ('$r[2]', '$r[3]', '$r[4]', '$r[5]', $r[6], $r[7])";
                mysqli_query($conn, $sql1);
                echo $sql1;
            } elseif ($r[0] == "T") {
                $sql2 = "INSERT INTO task VALUES ($r[1], '$r[2]')";
                echo $sql2;
                mysqli_query($conn, $sql2);
            } elseif ($r[0] == "U") {
                $sql3 = "INSERT INTO utenti VALUES ('$r[1]', '$r[2]', '$r[3]', '$r[4]')";
                mysqli_query($conn, $sql3);
                echo $sql3 . "<br>";
            } elseif ($r[0] == "S") {
                $sql3 = "INSERT INTO stati VALUES ($r[1], '$r[2]')";
                mysqli_query($conn, $sql3);
                echo $sql3 . "<br>";
            }
        }
        mysqli_close($conn);
    } else {
        echo ("Errore caricamento");
        die();
    }
    ?>
    <a href="logout.php">Pagina</a>
</body>

</html>
