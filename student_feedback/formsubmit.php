<?php 
require('../db_connection.php');

//se hva som har blitt sendt dra skjemaet: 
var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $subject = $_POST['subject'];
    $feedback = $_POST['subject_feedback'];

    //hindre SQL-INJECTIONS
    $subjectFeedback = mysqli_real_escape_string($conn, $feedback);


    $sql = "INSERT INTO emne_tilbakemeldinger (tilbakemelding, emnekode) VALUES ('$subjectFeedback', '$subject')";

    $result = $conn -> query($sql);

    if($result) {
        echo "<script>";
        echo "alert('Tilbakemeldingen din er mottatt!');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    } else {
        echo ("Error: " . $conn -> error);
    }

}
?>