<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <?php
    session_start();
    $msg = "";

    if (isset($_POST["loginUsername"]) && isset($_POST["loginPassword"])) {
        $username = $_POST["loginUsername"];
        $password = $_POST["loginPassword"];

        $conn = mysqli_connect("10.1.0.52", "5i1", "5i1", "5i1_BrugnoniAmantini");

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $query = "SELECT * FROM utente WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION["credenziali"] = $username;
            
            header("Location: pagina.php");
            exit;
        } else {
            $sql2 = "SELECT * FROM utente WHERE username='$username'";
            $result2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($result2) > 0) {
                $msg = "Password errata";
            } else {
                $msg = "Username errato";
            }
        }

        mysqli_close($conn);
    }
    echo $msg;
    if (!empty($msg)) {
        echo "<br><a href='logout.php'>Tenta di nuovo</a>";
    }
    ?>
</body>
</html>
