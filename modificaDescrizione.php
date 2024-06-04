<?php
include 'config/config.php';
$conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("connessione fallita: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dati_json = file_get_contents('php://input');
    $dati_decodificati = json_decode($dati_json, true);
    
    $stato = mysqli_real_escape_string($conn, $dati_decodificati['stato']);
    $descrizione = mysqli_real_escape_string($conn, $dati_decodificati['descrizione']);
    $utente = mysqli_real_escape_string($conn, $dati_decodificati['utente']);
    $task = mysqli_real_escape_string($conn, $dati_decodificati['task']);

    $sql = "INSERT INTO modifiche (fk_stato,descrizione,fk_utente,fk_task) VALUES ($stato, '$descrizione', '$utente', $task);";
    if (mysqli_query($conn, $sql)) {
        echo "Successo";
    } else {
        echo "Errore durante l'aggiornamento del record: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
