<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dati_decodificati = json_decode(file_get_contents("php://input"), true);
    if (isset($dati_decodificati["nome"]) && isset($dati_decodificati["descrizione"])) {
        $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");
        $nomeTask = mysqli_escape_string($conn,$dati_decodificati["nome"]);
        $descrizione = mysqli_escape_string($conn,$dati_decodificati["descrizione"]);
        $utente = mysqli_escape_string($conn, $_SESSION["credenziali"]);
        //$conn = mysqli_connect("10.1.0.52", "5i1", "5i1", "5i1_BrugnoniAmantini");
        if (!$conn) {
            die("Errore di connessione al database: " . mysqli_connect_error());
        }
        $sql = "INSERT INTO task (titolo) VALUES ('$nomeTask')";
        if (mysqli_query($conn, $sql)) {
            echo "Successo";
            $idTask = mysqli_insert_id($conn);
            $sql = "INSERT INTO modifiche (descrizione, fk_utente, fk_stato, fk_task) VALUES ('$descrizione', '$utente', 1, '$idTask')";
            if (mysqli_query($conn, $sql)) {
                echo "Modifiche aggiunte con successo";
            } else {
                echo "Errore durante l'aggiunta delle modifiche: " . mysqli_error($conn);
            }
        } else {
            echo "Errore durante l'aggiunta del task: " . mysqli_error($conn);
        }
        mysqli_close($conn);
    } else {
        echo  "Nome o descrizione del task mancanti.";
    }
} else {
    echo "Metodo non consentito";
}
