<?php
include 'config/config.php';
$conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$dati_json = file_get_contents('php://input');
$dati = json_decode($dati_json, true);

$titolo = mysqli_real_escape_string($conn, $dati['titolo']);
$id = mysqli_real_escape_string($conn, $dati['id']);
$descrizione = mysqli_real_escape_string($conn, $dati['descrizione']);
$stato = $dati['stato'] + 1;
$utente = mysqli_real_escape_string($conn, $dati['utente']);
$task = mysqli_real_escape_string($conn, $dati['task']);

$sql = "INSERT INTO modifiche (descrizione, fk_stato, fk_utente, fk_task) 
        VALUES ('$descrizione', $stato, '$utente', '$task')";

if (mysqli_query($conn, $sql)) {
    echo "Successo nell'inserimento";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?>