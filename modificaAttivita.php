<?php

$conn = mysqli_connect("localhost", "root", "", "kanban");

if (!$conn) {
    die("connessione fallita: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dati_json = file_get_contents('php://input');
    $dati_decodificati = json_decode($dati_json, true);
    $contenuto = mysqli_real_escape_string($conn, $dati_decodificati['contenuto']);
    $descrizione = mysqli_real_escape_string($conn, $dati_decodificati['descrizione']);
    $id = mysqli_real_escape_string($conn, $dati_decodificati['id']);
    $sql = "UPDATE stati SET nome = '$contenuto', descrizione = '$descrizione' WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        echo "Successo";
    } else {
        echo "Errore durante l'aggiornamento del record: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
