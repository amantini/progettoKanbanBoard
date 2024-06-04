<?php
include 'config/config.php';
$conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connessione fallita: " . mysqli_connect_error());
}

$filename = 'db_salvato.csv';
$file = fopen($filename, 'w');

function write_table_to_csv($conn, $table_name, $file, $prefix) {
    $query = "SELECT * FROM $table_name";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_unshift($row, $prefix);  // Aggiunge il prefisso come prima colonna
            $cleaned_row = array_map(function($value) {
                return str_replace('"', '', $value);  // Rimuove le virgolette
            }, $row);
            fwrite($file, implode(",", $cleaned_row) . "\n");  // Scrive la riga nel file
        }
    }
}
write_table_to_csv($conn, 'stati', $file, 'S');
write_table_to_csv($conn, 'task', $file, 'T');
write_table_to_csv($conn, 'utenti', $file, 'U');
write_table_to_csv($conn, 'modifiche', $file, 'M');

fclose($file);

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));
readfile($filename);
unlink($filename);
mysqli_close($conn);
exit;
?>
