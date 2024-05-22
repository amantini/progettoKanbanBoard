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
        foreach ($contenuto as $riga) {
            $r = explode(',', $riga);
            if ($r[0] == "M") {
                $sql1 = "insert into modifiche values ('$r[2]','$r[3]','$r[4]','$r[5]',$r[5],$r[6])";
                mysqli_query($conn, $sql1);
                echo $sql1;
            }
            if ($r[0] == "T") {
                $sql2 = "insert into task values ('$r[2]')";
                mysqli_query($conn, $sql2);
                echo $sql2;
            } else {
                $username = $r[1];
                $sql_check = "SELECT * FROM utenti WHERE username = '$username'";
                $result_check = mysqli_query($conn, $sql_check);
                
                if (mysqli_num_rows($result_check) > 0) {
                    echo "Username $username già esistente.<br>";
                } else {
                    $sql3 = "insert into utenti values ('$r[1]','$r[2]','$r[3]','$r[4]')";
                    mysqli_query($conn, $sql3);
                    echo $sql3 . "<br>";
                }
            }
        }
        mysqli_close($conn);
    } else {
        echo ("Errore caricamento");
        die();
    }
    ?>
    <a href="pagina.php">Pagina</a>
</body>

</html>
