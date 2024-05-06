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
        <div class="tab" ondragover="permettiDrop(event)" draggable="false">
            <div class="colonna" ondrop="drop(event)" id="col1" ondragover="permettiDrop(event)">
                <h3 class="titolo">Da Fare</h3>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col2" ondragover="permettiDrop(event)">
                <h3 class="titolo">In Esecuzione</h3>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col3" ondragover="permettiDrop(event)">
                <h3 class="titolo">Fatto</h3>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col4" ondragover="permettiDrop(event)">
                <h3 class="titolo">Terminato</h3>
            </div>
        </div>
        <?php
        $conn = mysqli_connect("localhost", "root", "", "kanban");
        $sql = "SELECT * FROM stati";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // creo contatore perché ho bisogno che ogni paragrafo abbia un id
            // affinchè possa renderli univoci per spostarli
            // parto da 5 perché sopra ho degli altri id dall'1 al 4
            while ($row = mysqli_fetch_assoc($result)) {
                $id = $row['id'];
                $nome = $row['nome'];
                $stato = $row['stati'];
                //$descrizione
                // avvio lo script
                echo "<script>";
                // creo un paragrafo impostando il testo, la classe (per il css), l'id (utile per il drag)
                // e le principali funzioni essenziali 
                echo "var p = document.createElement('p');";
                echo "p.innerText = '$nome';";
                echo "p.className = 'task';";
                echo "p.id='$id';";
                echo "p.draggable = true;";
                echo "p.style.cursor = 'move';";
                echo "p.onclick = function(event) {
                      alert(this.innerText);; // 'this' refers to the paragraph element
                    };";
                echo "p.ondragstart = 
                        function(event) { 
                            drag(event); 
                        };";
                // per organizzare le attività nelle colonne uso lo stato come indice
                echo "var cella = document.getElementById('col$stato');";
                // aggiungo al padre "cella" ogni paragrafo figlio
                echo "cella.appendChild(p);";
                echo "</script>";
            }
        }
        ?>
        <script>
            'use strict';
            function permettiDrop(event) {
                // visto che gli oggetti non sono di base trascinabili, stabilisco quelli che lo sono
                event.preventDefault();
            }

            function drag(event) {
                // stabilisco il tipo dei dati dell'elemento target dell'evento (id)
                event.dataTransfer.setData("text", event.target.id);
            }
            function drop(event) {
                event.preventDefault();
                var data = event.dataTransfer.getData("text");
                var elementoSelezionato = document.getElementById(data);
                // cerco, se esiste, la più vicina colonna dove lasciare l'elemento
                var colonnaRilascio = event.target.closest('.colonna');
                // se esiste
                if (colonnaRilascio != null) {
                    var idColonnaCorrente = elementoSelezionato.parentElement.id;
                    // controllo per spostarla solo avanti e non indietro!
                    if (parseInt(colonnaRilascio.id.replace('col', '')) > parseInt(idColonnaCorrente.replace('col', ''))) {
                        colonnaRilascio.appendChild(elementoSelezionato);
                        rilascio(data);
                    }
                }
            }
            async function rilascio(data) {
                var daInviare = parseInt(data);
                const risposta = await fetch(`modifica.php`, {
                    method: "POST",
                    body: JSON.stringify({
                        dato: daInviare
                    }),
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                });
            }
        </script>
    </div>
</body>

</html>