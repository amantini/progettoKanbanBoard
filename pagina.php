<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kanban Board</title>
    <link rel="stylesheet" href="styles.css">
    <!-- LO STILE SERVE SOLO PER I MODAL, NEL FILE CSS NON FUNZIONAVA E NON HO AVUTO TEMPO DI CONTROLLARE IL MOTIVO -->
    <style>
        .header {
            padding: 20px;
            background-color: #f1f1f1;
            display: flex;
            justify-content: flex-end;
        }

        .header button {
            padding: 10px 20px;
            margin-left: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 2px solid grey;
            background: transparent;
            outline: none;
        }

        .form-container input:focus {
            border-bottom-color: black;
        }

        .form-container button {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- FINO A DIV PAGINA E' TUTTO MODAL -->
    <div class="header">
        <button onclick="showModal('loginModal')">Login</button>
        <button onclick="showModal('registerModal')">Registrati</button>
    </div>
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <h2>Login</h2>
            <form>
                <div class="form-container">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <button type="submit">Accedi</button>
                </div>
            </form>
        </div>
    </div>
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('registerModal')">&times;</span>
            <h2>Registrati</h2>
            <form>
                <div class="form-container">
                    <label for="new-username">Username:</label>
                    <input type="text" id="new-username" name="new-username" required>
                    <label for="new-password">Password:</label>
                    <input type="password" id="new-password" name="new-password" required>
                    <button type="submit">Registrati</button>
                </div>
            </form>
        </div>
    </div>
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
        $conn = mysqli_connect("localhost", "root", "", "5i1_kanban");
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
            // ----TODO descrizione dell'attività al click, non scompare e ricompare come dovrebbe, problema con i child
            var num = 0;

            function mostraDescrizione(event) {
                console.log(event.target.id);

                const p = document.getElementById(event.target.id);
                const descrizione = document.createElement("p");
                descrizione.innerText = "ciao";
                if (num == 0) {
                    p.appendChild(descrizione);
                    num++;
                } else
                if (num == 1) {
                    p.removeChild(descrizione);
                    num = 0;
                }

            }
            // fare vedere i popup (modal)
            function showModal(modalId) {
                document.getElementById(modalId).style.display = 'block';
            }

            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }
        </script>
    </div>
</body>

</html>