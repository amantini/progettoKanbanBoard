<?php

$conn = mysqli_connect("localhost", "root", "", "kanban");

if (!$conn) {
    die("connessione fallita: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dati_json = file_get_contents('php://input');
    $dati_decodificati = json_decode($dati_json, true);
    $id_attivita = mysqli_real_escape_string($conn, $dati_decodificati['dato']);
    $sql = "UPDATE stati SET stati = stati + 1  WHERE id = $id_attivita";
    if (mysqli_query($conn, $sql)) {
        echo "Successo";
    } else {
        echo "Errore durante l'aggiornamento del record: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
