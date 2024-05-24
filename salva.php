<?php
// Step 1: Converti l'array in una stringa JSON
$array = [
    'nome' => 'Mario',
    'cognome' => 'Rossi',
    'email' => 'mario.rossi@example.com'
];
$jsonData = json_encode($array);

// Step 2: Salva la stringa JSON in un file
$filename = 'dati.json';
file_put_contents($filename, $jsonData);

// Step 3: Imposta gli header per il download del file
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));

// Pulisci l'output buffer
ob_clean();
flush();

// Leggi il file e invialo al client
readfile($filename);

// Rimuovi il file dal server dopo il download (opzionale)
unlink($filename);
exit;
?>
