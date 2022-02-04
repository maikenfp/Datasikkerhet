<?php
// Start the session
session_start();
require '.././config/Database.php';

$database = new Database();
$db = $database->connect();

//se hva som har blitt sendt dra skjemaet: var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentStudentId = $_SESSION["student_id"];

    $subject = $_POST['subject'];
    $question = $_POST['subjectQuestion'];

    $date = date('Y-m-d');

    $sql = "INSERT INTO melding(spørsmål, emne_id, student_id, dato, tid) 
    VALUES ('$question', '$subject', '$currentStudentId', '$date', (NOW()))";
    $result = $db->query($sql);

    if ($result) {
        echo "<script>";
        echo "alert('Tilbakemeldingen din er mottatt!');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cid = $_GET['cid'];
    echo getForeleserBilde($cid);
}

function getForeleserBilde($pin)
{
    $database = new Database();
    $db = $database->connect();
    $emneID = $pin;
    $bilde = "";


    $query = "SELECT bilde_navn FROM foreleser f 
    JOIN foreleser_emne fe on fe.foreleser_id = f.foreleser_id WHERE emne_id = '$emneID'";

    $stmt = $db->query($query);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($row) {
        foreach ($row as $row) {
            $bilde_navn = $row["bilde_navn"];
            $bilde = $bilde_navn;
        }
    }


    return json_encode($bilde, JSON_PRETTY_PRINT);
}
