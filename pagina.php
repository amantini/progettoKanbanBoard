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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    $utente = $_SESSION["credenziali"];
    $conn = mysqli_connect("localhost", "root", "", "5i1_BrugnoniAmantini");
    $sql = "SELECT * FROM utenti WHERE username='$utente'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $nome = $row['nome'];
        $cognome = $row['cognome'];
    }
    ?>
    <div class="pagina">
        <div class="forms-container">
            <h1>KanbanBoard</h1>
            <div>
                <div id="modalLog" class="modal">
                    <div class="modal-content-log">
                        <span class="close-log">&times;</span>
                        <h2>Log Modifiche</h2>
                        <table id="tabModifiche">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data</th>
                                    <th>Ora</th>
                                    <th>Descrizione</th>
                                    <th>Utente</th>
                                    <th>Stato</th>
                                    <th>Task</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Le righe della tabella verranno inserite qui tramite JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="dropdown">
                <button onclick="dropdown()" class="btn-drop"><?php echo $utente ?></button>
                <div id="div-dropdown" class="dropdown-content">
                    <img src="omino.png" width="90px" height="90px">
                    <p><?php echo "$nome $cognome" ?></p>
                    <!--<p><?php //echo strtolower("$nome$cognome@gmail.com")
                            ?></p>!-->
                    <a href="gestioneaccount.php">Il mio account</a>
                    <a href="#" onclick="caricaDatabase()" id="caricaLink">Carica</a>
                    <a href="carica.php">Salva</a>
                    <a href="help.html">Aiuto</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="tab" ondragover="permettiDrop(event)" draggable="false">
            <div class="colonna" ondrop="drop(event)" id="col1" ondragover="permettiDrop(event)">
                <h3 class="titolo">Da Fare <span class="span-col" id="span1"></span></h3>
                <button class="btn-aggiungi">+</button>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col2" ondragover="permettiDrop(event)">
                <h3 class="titolo">In Esecuzione <span class="span-col" id="span2"></span></h3>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col3" ondragover="permettiDrop(event)">
                <h3 class="titolo">Fatto <span class="span-col" id="span3"></span></h3>
            </div>
            <div class="colonna" ondrop="drop(event)" id="col4" ondragover="permettiDrop(event)">
                <h3 class="titolo">Terminato <span class="span-col" id="span4"></span></h3>
            </div>
        </div>
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <form id="form" action="pagina.php">
                    <h2>Aggiungi attività </h2>
                    <input type="text" placeholder="Nuova attività..." id="attivitaInput" required />
                    <h2>Aggiungi descrizione</h2>
                    <input type="text" placeholder="Descrizione..." id="descrizioneInput" required />
                    <button type="submit" id="bottoneAggiungi" onclick="aggiungiTask()">Aggiungi +</button>
                </form>
            </div>
        </div>

    </div>

    <?php
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
            $titolo = addslashes($row['titolo']);
            $id = addslashes($row['id']);
            $ora = addslashes($row['ora']);
            $data = addslashes($row['data']);
            $descrizione = addslashes($row['descrizione']);
            $stato = addslashes($row['fk_stato']);
            $utente = addslashes($_SESSION["credenziali"]);
            $task = addslashes($row['fk_task']);
            echo "<script>";
            // Creo un paragrafo per il titolo dell'attività
            echo "var p = document.createElement('p');";
            echo "p.setAttribute('data-titolo', '" . $titolo . "');";
            echo "p.setAttribute('data-id', '" . $id . "');";
            echo "p.setAttribute('data-descrizione', '" . $descrizione . "');";
            echo "p.setAttribute('data-stato', '" . $stato . "');";
            echo "p.setAttribute('data-utente', '" . $utente . "');";
            echo "p.setAttribute('data-task', '" . $task . "');";
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
            echo "var bottone = document.createElement('button');";
            echo "bottone.id='bottoneLog$task';";
            echo "bottone.innerText='Storico modifiche';";
            echo "bottone.onclick = function(event) {cercaLog(event,$task);};";
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
            echo "div.appendChild(bottone);";

            // Aggiungo il div e il paragrafo alla colonna
            echo "cella.appendChild(p);";
            echo "p.appendChild(div);";
            echo "</script>";
        }
    }
    ?>
    <script>
        'use strict';
        mostraQuante();

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
                    mostraQuante();
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
            /*console.log("ID:", id);
            console.log("Descrizione:", descrizione);
            console.log("Stato:", stato);
            console.log("Utente:", utente);
            console.log("Task:", task);*/
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
        
        function mostraModificaDescrizione(event) {
            const vettoreDiv = document.querySelectorAll('.info-container');
            if (event.target.tagName.toLowerCase() === 'p' && event.target.classList.contains('task')) {
                const p = document.getElementById(event.target.id);
                var stato = parseInt(p.dataset.stato);
                var utente = p.dataset.utente;
                var task = parseInt(p.dataset.task);
                const div = document.getElementById("div" + event.target.id);
                const descrizione = document.getElementById("descrizione" + event.target.id);
                vettoreDiv.forEach(desc => {
                    desc.style.display = "none";
                });
                if (div.style.display === "block") {
                    div.style.display = "none";
                    document.removeEventListener("dblclick", gestisciDoppioClicl);
                    descrizione.removeEventListener("keydown", gestisciInvio);
                } else {
                    div.style.display = "block";
                    document.addEventListener("dblclick", gestisciDoppioClicl);
                    descrizione.addEventListener("keydown", gestisciInvio);
                }
            }
        }


        function gestisciDoppioClicl(event) {
            if (event.target.tagName.toLowerCase() === 'p' && event.target.classList.contains('descrizione-task')) {
                if (event.target.contentEditable === 'true') {
                    event.target.contentEditable = false;
                } else {
                    event.target.contentEditable = true;
                }
                event.target.focus();
            }
        }

        function gestisciInvio(event) {
            if (event.key === "Enter") {
                event.preventDefault();
                const descrizioneElementi = document.querySelectorAll('.descrizione-task');
                descrizioneElementi.forEach(desc => {
                    desc.blur();
                });
                inviaDati(descrizione.innerText, stato, utente, task);

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
        var modal = document.getElementById("myModal");
        var btn = document.querySelector(".btn-aggiungi");
        var span = document.getElementsByClassName("close")[0];
        btn.onclick = function() {
            modal.style.display = "block";
        }
        span.onclick = function() {
            modal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function dropdown() {
            document.getElementById("div-dropdown").classList.toggle("show");
        }
        window.onclick = function(event) {
            if (!event.target.matches('.btn-drop')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        function mostraQuante() {
            var colonne = document.querySelectorAll('.colonna');
            colonne.forEach(function(colonna, indice) {
                var conteggioParagrafi = colonna.querySelectorAll('.task').length;
                var span = document.getElementById('span' + (indice + 1));
                span.innerText = "" + conteggioParagrafi;
            });
        }

        function apriLog(evento) {
            const myTimeout = setTimeout(mostraLog, 1000);

            function mostraLog() {
                window.location.href = "log.php";
            }

            function myStopFunction() {
                clearTimeout(myTimeout);
            }
        }
        async function cercaLog(event, task) {
            let risposta = await fetch("log.php?task=" + task);
            let modifiche = await risposta.json();
            console.log(modifiche);
            let tableRows = '';
            modifiche.forEach(item => {
                tableRows += `
            <tr>
                <td>${item.id}</td>
                <td>${item.data}</td>
                <td>${item.ora}</td>
                <td>${item.descrizione}</td>
                <td>${item.fk_utente}</td>
                <td>${item.fk_stato}</td>
                <td>${item.fk_task}</td>
            </tr>
        `;
            });

            document.querySelector('#tabModifiche tbody').innerHTML = tableRows;

            // Mostra il modal
            let modalLog = document.getElementById("modalLog");
            modalLog.style.display = "block";

            // Chiudi il modal quando si clicca sulla X
            let spanLog = document.getElementsByClassName("close-log")[0];
            spanLog.onclick = function() {
                modalLog.style.display = "none";
            }

            // Chiudi il modal quando si clicca fuori dal modal
            window.onclick = function(event) {
                if (event.target == modalLog) {
                    modalLog.style.display = "none";
                }
            }
        }

        function salvaDatabase() {
            alert("Database salvato con successo su file CSV!");
        }

        function caricaDatabase() {
            alert("Caricamento del database in corso...");
        }
    </script>

    </div>
</body>

</html>