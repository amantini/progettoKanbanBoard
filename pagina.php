<?php
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
            <form action="logout.php" method="POST" id="logout">
                <button type="submit">Logout</button>
            </form>
        </div>
        <div class="tab" ondragover="permettiDrop(event)" draggable="false">
            <div class="colonna" ondrop="drop(event)" id="col1" ondragover="permettiDrop(event)">
                <h3 class="titolo">Da Fare</h3>
                <button class="btn-aggiungi">+</button>
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

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="form">
                <input type="text" placeholder="Nuova attività..." id="attivitaInput" required />
                <input type="text" placeholder="Descrizione..." id="descrizioneInput" required />
                <button type="submit" id="bottoneAggiungi" onclick="aggiungiTask()">Aggiungi +</button>
            </form>
        </div>
    </div>
    <?php
    $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");
    //$conn = mysqli_connect("10.1.0.52", "5i1", "5i1", "5i1_BrugnoniAmantini");
    $sql = "SELECT  task.titolo, modifiche.*
                        FROM task,  modifiche, utenti
                            WHERE fk_utente = utenti.username
                            AND fk_task = task.id
                            AND (task.id, modifiche.id) IN 
                            (
                                SELECT modifiche.fk_task , MAX(modifiche.id) as max_id
                                FROM modifiche
                                GROUP BY modifiche.fk_task
                            );";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // creo contatore perché ho bisogno che ogni paragrafo abbia un id
        // affinchè possa renderli univoci per spostarli
        // parto da 5 perché sopra ho degli altri id dall'1 al 4
        while ($row = mysqli_fetch_assoc($result)) {
            $titolo = $row['titolo'];
            $id = $row['id'];
            $ora = $row['ora'];
            $data = $row['data'];
            $descrizione = $row['descrizione'];
            $stato = $row['fk_stato'];
            $utente = $_SESSION["credenziali"];
            $task = $row['fk_task'];
            echo "<script>";
            // Creo un paragrafo per il titolo dell'attività
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
            echo "p.onclick = function(event) { mostraModificaDescrizione(event); };";
            echo "p.ondragstart = function(event) { drag(event); };";
            echo "var cella = document.getElementById('col$stato');";
            // Creo un div per contenere la descrizione
            echo "var div = document.createElement('div');";
            echo "div.id = 'div$id';"; // Imposto un ID univoco per il div
            echo "div.className = 'info-container';"; // Aggiungo una classe al div

            // Creo un paragrafo per la descrizione
            echo "var descrizione = document.createElement('p');";
            echo "descrizione.id = 'descrizione$id';"; // Imposto un ID univoco per la descrizione
            echo "descrizione.className='descrizione-task';";
            echo "div.style.display='none';";
            echo "descrizione.innerText = '" . $descrizione . "';";

            // Creo un titolo per la descrizione
            echo "var titoloDesc = document.createElement('h5');";
            echo "titoloDesc.id = 'titoloDesc$id';"; // Imposto un ID univoco per il titolo della descrizione
            echo "titoloDesc.innerText = 'Descrizione';";
            echo "titoloDesc.style.paddingTop = '1em';";
            // Aggiungo la descrizione e il titolo della descrizione come figli del div
            echo "div.appendChild(titoloDesc);";
            echo "div.appendChild(descrizione);";

            // Aggiungo il div e il paragrafo alla colonna
            echo "cella.appendChild(p);";
            echo "p.appendChild(div);";
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
            var colonnaRilascio = event.target.closest('.colonna');
            if (colonnaRilascio != null) {
                var idColonnaCorrente = elementoSelezionato.parentElement.id;
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

        function mostraModificaDescrizione(event) {
            const vettoreDiv = document.querySelectorAll('.info-container');
            const descrizione = document.getElementById("descrizione" + event.target.id);
            if (event.target.tagName.toLowerCase() === 'p' && event.target.classList.contains('task')) {
                const p = document.getElementById(event.target.id);
                var elementoSelezionato = document.getElementById(event.target.id);
                var stato = parseInt(elementoSelezionato.dataset.stato);
                var utente = elementoSelezionato.dataset.utente;
                var task = parseInt(elementoSelezionato.dataset.task);
                const div = document.getElementById("div" + event.target.id);
                const descrizione = document.getElementById("descrizione" + event.target.id);
                if (num === 0) {
                    div.style.display = "block";
                    document.addEventListener("dblclick", function(doubleClickEvent) {
                        if (doubleClickEvent.target.tagName.toLowerCase() === 'p' && doubleClickEvent.target.classList.contains('descrizione-task')) {
                            if (doubleClickEvent.target.contentEditable === 'true') {
                                doubleClickEvent.target.contentEditable = false;
                            } else {
                                doubleClickEvent.target.contentEditable = true;
                            }
                            doubleClickEvent.target.focus();
                        }
                    });

                    descrizione.addEventListener("keydown", function(keyEvent) {
                        if (keyEvent.key === "Enter") {
                            keyEvent.preventDefault();
                            inviaDati(descrizione.innerText, stato, utente, task);
                            const descrizioneElementi = document.querySelectorAll('.descrizione-task');
                            descrizioneElementi.forEach(desc => {
                                desc.style.contentEditable = false;
                            });
                        }
                    });

                    num++;
                } else {
                    vettoreDiv.forEach(desc => {
                        desc.style.display = "none";
                    });
                    num = 0;
                }
            }



        }

        async function inviaDati(contenutoDescrizione, stato, utente, task) {
            var inviaDescrizione = contenutoDescrizione;
            const risposta = await fetch(`modificaDescrizione.php`, {
                method: "POST",
                body: JSON.stringify({
                    descrizione: inviaDescrizione,
                    stato: stato,
                    utente: utente,
                    task: task
                }),
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                }
            });
        }
        async function aggiungiTask() {
            var nomeTask = attivitaInput.value;
            var descrizioneTask = descrizioneInput.value;
            console.log(nomeTask);
            console.log(descrizioneTask);
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

        // Ottieni il modal
        var modal = document.getElementById("myModal");

        // Ottieni il pulsante che apre il modal
        var btn = document.querySelector(".btn-aggiungi");

        // Ottieni l'elemento per chiudere il modal
        var span = document.getElementsByClassName("close")[0];

        // Quando l'utente clicca sul pulsante, apri il modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // Quando l'utente clicca sull'elemento di chiusura, chiudi il modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Quando l'utente clicca ovunque al di fuori del modal, chiudi il modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    </div>
</body>

</html>