<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kanban Board</title>
    <link rel="stylesheet" href="styles.css">
    <!-- LO STILE SERVE SOLO PER I MODAL, NEL FILE CSS NON FUNZIONAVA E NON HO AVUTO TEMPO DI CONTROLLARE IL MOTIVO -->
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
            $descrizione = $row['descrizione'];
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
    </script>
    </div>
</body>

</html>