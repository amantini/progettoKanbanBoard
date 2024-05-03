<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kanban Board</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="pagina">
        <form id="form">
            <input type="text" placeholder="Nuova attività..." id="input" />
            <button type="submit" formaction="aggiungi.php">Aggiungi +</button>
        </form>
        <div class="tab">
            <div class="colonne" id="1">
                <h3 class="titolo">Da Fare</h3>
            </div>
            <div class="colonne" id="2">
                <h3 class="titolo">In Esecuzione</h3>
            </div>
            <div class="colonne" id="3">
                <h3 class="titolo">Fatto</h3>
            </div>
            <div class="colonne" id="4">
                <h3 class="titolo">Terminato</h3>
            </div>
        </div>
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
                echo "p.className = 'task';"; 
                echo "var cella = document.getElementById('$stato');";
                echo "cella.appendChild(p);";
                echo "</script>";
            }
        }
        ?>
    </div>
</body>

</html>
