<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sposta un elemento &lt;p&gt; in una tabella</title>
<style>
    
</style>
</head>
<body>

<table id="myTable" border="1">
  <tr>
    <td>1</td>
    <td>2</td>
    <td>3</td>
    <td>4</td>
  </tr>
  <tr>
    <td id="1"><p></p><button onclick="spostaAttivita('1')">Sposta a sinistra</button></td>
    <td id="2"><p></p><button onclick="spostaAttivita('2')">Sposta a sinistra</button></td>
    <td id="3"><p></p><button onclick="spostaAttivita('3')">Sposta a sinistra</button></td>
    <td id="4"><p></p><button onclick="spostaAttivita('4')">Sposta a sinistra</button></td>
  </tr>
</table>

<?php
$conn = mysqli_connect("localhost", "root", "", "kanban");
$sql = "SELECT * FROM stati";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nome = $row['nome'];
        $stato = $row['stati'];
        echo "<script>";
        echo "var p = document.createElement('p');";
        echo "p.innerText = '$nome';";
        echo "var cella = document.getElementById('$stato');";
        echo "cella.querySelector('button').insertAdjacentElement('beforebegin', p);"; 
        echo "</script>";
    }
}
?>

<script>
// Funzione per spostare l'attività alla colonna precedente
function spostaAttivita(colonna) {
    var currentCell = document.getElementById(colonna);
    var nextCell = currentCell.nextElementSibling;
    var activity = currentCell.querySelector('p');
    currentCell.removeChild(activity); // Rimuove il paragrafo dalla cella corrente
    if (nextCell !== null ) {
        nextCell.appendChild(activity); // Sposta il paragrafo nella cella successiva
    }
}
</script>

</body>
</html>
