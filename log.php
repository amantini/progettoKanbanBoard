    <?php
    include 'config/config.php';

    $conn = mysqli_connect($dbIp, $dbUsername, $dbPassword, $dbName);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $id = $_GET["task"];
    $sql = "select * from modifiche where fk_task=$id";
    $result = mysqli_query($conn, $sql);
    $a = array();
    if (mysqli_num_rows($result) > 0) {
        while ($riga = mysqli_fetch_assoc($result)) {
            array_push($a, $riga);
        }
        echo json_encode($a);
    } else {
        echo json_encode($a);
    }

    mysqli_close($conn);
    ?>
