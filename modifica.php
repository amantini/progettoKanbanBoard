<?php
$conn = mysqli_connect("localhost", "root", "", "kanban");
if (isset($_POST["dato"])) {
    $data = json_decode(file_get_contents('php://input'), true);
    $var = $data['dato'];
   
    $sql="SELECT * FROM stati where id='$var'";
    $result=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
            echo json_encode($row);
        }
    }
}
?>