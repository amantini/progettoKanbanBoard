<?php
$host = 'localhost';
$dbname = '5i1_brugnoniamantini';
$username = 'root';
$password = '';
$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$filename = 'db_salvato.csv';

$file = fopen($filename, 'w');

function write_table_to_csv($conn, $table_name, $file, $prefix) {
    $query = "SELECT * FROM $table_name";
    $result = mysqli_query($conn, $query);
    
    // Verifica se ci sono risultati
    if (mysqli_num_rows($result) > 0) {
        // Recupera i nomi delle colonne
        $fields = mysqli_fetch_fields($result);
        $column_names = [$prefix];
        foreach ($fields as $field) {
            $column_names[] = $field->name;
        }
        // Scrivi i nomi delle colonne nel file CSV
        fputcsv($file, $column_names);
        
        while ($row = mysqli_fetch_assoc($result)) {
            array_unshift($row, $prefix);
            fputcsv($file, $row);
        }
    }
}

write_table_to_csv($conn, 'modifiche', $file, 'm');
write_table_to_csv($conn, 'task', $file, 't');
write_table_to_csv($conn, 'utenti', $file, 'u');

fclose($file);

header('Content-Description: File Transfer');
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filename));

ob_clean();
flush();

readfile($filename);

unlink($filename);

mysqli_close($conn);
exit;
?>
