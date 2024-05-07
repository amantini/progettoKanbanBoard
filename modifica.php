<?php

$conn = mysqli_connect("localhost", "root", "", "5i1_kanban");

if (!$conn) {
    die("connessione fallita: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $dati_json = file_get_contents('php://input');
    $dati_decodificati = json_decode($dati_json, true);
    $id_attivita = $dati_decodificati['dato'];

    $sql = "UPDATE stati SET stati = stati + 1 WHERE id = $id_attivita";
    //$sql="UPDATE stati SET stati = 2 IF stati=1 AND SET stati= 3 IF stati=2 AND SET stati=4 IF stati=3"
    /*$sql = "UPDATE stati SET stati=
            CASE
                WHEN stati=1 THEN 2
                WHEN stati=2 THEN 3
                WHEN stati=3 THEN 4
            END
            WHERE id=$id_attivita;";*/

    if (mysqli_query($conn, $sql)) {
        echo "Successo";
    } else {
        echo "Errore durante l'aggiornamento del record: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
