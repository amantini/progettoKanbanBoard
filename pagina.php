<?php
// FIX!
session_start();
if (!isset($_SESSION["credenziali"])) {
    header("Location: indice.php");
    exit();
}
?>
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
        <div class="forms-container">
            <form id="form">
                <input type="text" placeholder="Nuova attività..." id="attivitaInput" required />
                <input type="text" placeholder="Descrizione..." id="descrizioneInput" required />
                <button type="submit" onclick="aggiungiTask()" id="bottoneAggiungi">Aggiungi +</button>
            </form>
            <form action="logout.php" method="POST" id="logout">
                <button type="submit">Logout</button>
            </form>
        </div>
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
    </div>
    <?php
    $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");
    //$conn = mysqli_connect("10.1.0.52", "5i1", "5i1", "5i1_BrugnoniAmantini");
    $sql = "SELECT  task.titolo, modifiche.*
            FROM task,  modifiche, utenti
                WHERE fk_utente = utenti.username
                AND fk_task = task.id
                AND (task.id, utenti.username, modifiche.id) IN 
                (
                    SELECT modifiche.fk_task, modifiche.fk_utente, MAX(modifiche.id) as max_id
                    FROM modifiche
                    GROUP BY modifiche.fk_task, modifiche.fk_utente
                );";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // creo contatore perché ho bisogno che ogni paragrafo abbia un id
        // affinchè possa renderli univoci per spostarli
        // parto da 5 perché sopra ho degli altri id dall'1 al 4
        while ($row = mysqli_fetch_assoc($result)) {
            $titolo = $row['titolo'];
            $id = $row['id'];
            $descrizione = $row['descrizione'];
            $stato = $row['fk_stato'];
            $utente = $_SESSION["credenziali"];
            $task = $row['fk_task'];
            echo "<script>";
            // creo un paragrafo impostando il testo, la classe (per il css), l'id (utile per il drag)
            // e le principali funzioni essenziali 
            echo "var p = document.createElement('p');";
            echo "p.setAttribute('data-titolo', '" . htmlspecialchars($titolo) . "');";
            echo "p.setAttribute('data-id', '" . htmlspecialchars($id) . "');";
            echo "p.setAttribute('data-descrizione', '" . htmlspecialchars($descrizione) . "');";
            echo "p.setAttribute('data-stato', '" . htmlspecialchars($stato) . "');";
            echo "p.setAttribute('data-utente', '" . htmlspecialchars($utente) . "');";
            echo "p.setAttribute('data-task', '" . htmlspecialchars($task) . "');";
            echo "p.innerText = '$titolo';";
            echo "p.className = 'task';";
            echo "p.id='$id';";
            echo "p.draggable = true;";
            echo "p.style.cursor = 'move';";
            echo "p.onclick = 
                        function(event) {
                            mostraDescrizione(event); 
                        };";
            echo "p.ondragstart = 
                        function(event) { 
                            drag(event); 
                        };";
            echo "p.onmousedown = function(event) { modifica(event);};";
            echo "var descrizione = document.createElement('p');";
            echo "descrizione.id='descrizione$id';";
            echo "descrizione.style.display='none';";
            echo "descrizione.innerText = '" . $descrizione . "';";
            // per organizzare le attività nelle colonne uso lo stato come indice
            echo "var cella = document.getElementById('col$stato');";
            echo "var titoloDesc= document.createElement('h5');";
            echo "titoloDesc.id='titoloDesc$id';";
            echo "titoloDesc.innerText='Descrizione';";
            echo "titoloDesc.style.display='none';";
            echo "titoloDesc.style.paddingTop='1em';";
            echo "titoloDesc.className = 'desc-titolo';";
            // aggiungo al padre "cella" ogni paragrafo figlio
            echo "p.appendChild(titoloDesc);";
            echo "p.appendChild(descrizione);";
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
            var elementoSelezionato = document.getElementById(data);
            var id = parseInt(elementoSelezionato.dataset.id);
            var descrizione = elementoSelezionato.dataset.descrizione;
            var stato = parseInt(elementoSelezionato.dataset.stato);
            var utente = elementoSelezionato.dataset.utente;
            var task = elementoSelezionato.dataset.task;

            console.log("ID:", id);
            console.log("Descrizione:", descrizione);
            console.log("Stato:", stato);
            console.log("Utente:", utente);
            console.log("Task:", task);

            const risposta = await fetch(`modifica.php`, {
                method: "POST",
                body: JSON.stringify({
                    id: id,
                    descrizione: descrizione,
                    stato: stato,
                    utente: utente,
                    task: task
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });
        }
        // ----TODO descrizione dell'attività al click, non scompare e ricompare come dovrebbe, problema con i child
        var num = 0;

        function mostraDescrizione(event) {
            if (num === 0) {
                const p = document.getElementById(event.target.id);
                const titoloDescrizione = document.getElementById("titoloDesc" + event.target.id);
                const descrizione = document.getElementById("descrizione" + event.target.id);
                if (descrizione.style.display === "none" || descrizione.style.display === "") {
                    titoloDescrizione.style.display = "block";
                    descrizione.style.display = "block";
                }
                num++;
            } else {
                const allDescrizioni = document.querySelectorAll('[id^="descrizione"]');
                const allTitoli = document.querySelectorAll('[id^="titoloDesc"]');
                allDescrizioni.forEach(desc => {
                    desc.style.display = "none";
                });
                allTitoli.forEach(titolo => {
                    titolo.style.display = "none";
                });
                num = 0;
            }
        }

        document.addEventListener("contextmenu", function(event) {
            if (event.target.tagName.toLowerCase() === "p" || event.target.tagName.toLowerCase() === "h5") {
                event.preventDefault();
            }
        });

        function modifica(event) {
            var id = parseInt(event.target.id);
            if (event.button == 2) {
                const p = document.getElementById(event.target.id);
                const titolo = document.getElementById("titoloDesc" + event.target.id)
                const descrizione = document.getElementById("descrizione" + id);
                p.style.display = "block";
                titolo.style.display = "block";
                descrizione.style.display = "block";
                p.contentEditable = true;
                const allTitoli = document.querySelectorAll('[id^="titoloDesc"]');
                allTitoli.forEach(titolo => {
                    titolo.contentEditable = false;
                });
                p.addEventListener("keydown", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        p.contentEditable = false;
                        var contenuto = p.innerText.split('\n')[0].trim();
                        var contenutoDescrizione = descrizione.innerText;
                        inviaModificaAtt(contenuto, contenutoDescrizione, id);
                        p.style.display = "block";
                        titolo.style.display = "none";
                        descrizione.style.display = "none";
                    }
                });
            }
        }
        async function inviaModificaAtt(contenuto, contenutoDescrizione, id) {
            var inviaContenuto = contenuto;
            var inviaDescrizione = contenutoDescrizione;
            var inviaId = id;
            const risposta = await fetch(`modificaAttivita.php`, {
                method: "POST",
                body: JSON.stringify({
                    contenuto: inviaContenuto,
                    descrizione: inviaDescrizione,
                    id: inviaId
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });
        }
        async function aggiungiTask() {
            var nomeTask = attivitaInput.value;
            var descrizioneTask = descrizioneInput.value;
            if (/[a-zA-Z]/.test(nomeTask) && /[a-zA-Z]/.test(descrizioneTask)) {
                const risposta = await fetch(`aggiungi.php`, {
                    method: "POST",
                    body: JSON.stringify({
                        nome: nomeTask,
                        descrizione: descrizioneTask
                    }),
                    headers: {
                        "Content-type": "application/json; charset=UTF-8"
                    }
                });
            }
        }
    </script>
    </div>
</body>

</html>