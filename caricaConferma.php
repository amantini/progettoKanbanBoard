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
    if (!isset($_SESSION["credenziali"])) {
        header("Location: indice.php");
        exit;
    }
    $conn = mysqli_connect("localhost", "root", "", "5i1_brugnoniamantini");
    if ($_FILES["nomeFile"]["error"] == 0) {
        $contenuto = file($_FILES["nomeFile"]["tmp_name"]);
        $sqlDelete1 = "delete from utenti";
        $sqlDelete2 = "delete from task";
        mysqli_query($conn, $sqlDelete1);
        mysqli_query($conn, $sqlDelete2);
        foreach ($contenuto as $riga) {
            $r = explode(',', $riga);
            if ($r[0] == "M") {
                $sql1 = "INSERT INTO modifiche (data,ora,descrizione,fk_utente,fk_stato,fk_task) VALUES ('$r[2]', '$r[3]', '$r[4]', '$r[5]', $r[6], $r[7])";
                mysqli_query($conn, $sql1);
                echo $sql1;
            } elseif ($r[0] == "T") {
                $sql2 = "INSERT INTO task  VALUES ($r[1],'$r[2]')";
                echo $sql2;
                mysqli_query($conn, $sql2);
                
            } elseif ($r[0] == "U") {
                $sql3 = "INSERT INTO utenti VALUES ('$r[1]', '$r[2]', '$r[3]', '$r[4]')";
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
    <a href="index.php">Pagina</a>
</body>

</html>