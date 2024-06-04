<?php
include 'config/config.php';
$conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$dati_json = file_get_contents('php://input');
$dati = json_decode($dati_json, true);

$nome = mysqli_real_escape_string($conn, $dati['nome']);
$cognome = mysqli_real_escape_string($conn, $dati['cognome']);
$username = mysqli_real_escape_string($conn, $dati['username']);
$password = mysqli_real_escape_string($conn, $dati['password']);

$sql = "UPDATE utenti SET nome='$nome', cognome='$cognome', password='$password' WHERE username='$username'";

if (mysqli_query($conn, $sql)) {
    if (mysqli_affected_rows($conn) > 0) {
        echo "Modificato";
    } else {
        echo "Nessun cambiamento trovato";
    }
} else {
    echo  "Errore: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
