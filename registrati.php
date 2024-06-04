<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrati</title>
</head>
<body>
    <?php 
        include 'config/config.php';
        $conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        $username= $_POST["registraUsername"];
        $nome= $_POST["registraNome"];
        $cognome= $_POST["registraCognome"];
        $password= $_POST["registraPassword"];
        $sql= "SELECT username FROM utente where username = '$username'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result) > 0){
            echo "Utente già registrato";
        } else {
            $sql= "INSERT INTO utente VALUES('$username','$nome','$cognome','$password');";
            if(mysqli_query($conn,$sql)){
                echo "Utente registrato con successo!";
            } else {
                echo "Errore nell'inserimento";
            }
        }
    ?>
</body>
</html>