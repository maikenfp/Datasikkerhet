<?php
// Start the session
session_start();
require '../config/Database.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Form variabler - htmlspecialchars hindrer XSS ("<" blir "lt" og ">" blir "gt")
    $currentStudentId = $_SESSION["student_id"];
    $question = htmlspecialchars($_POST['subjectQuestion']);
    $date = date('Y-m-d');

    //Sjekker om dropdown option er numeric eller ikke (hindrer sql injection og XSS i inspect element)
    if (isset($_POST['subject'])) {
        htmlspecialchars($subject = $_POST['subject']);

        if (!(is_numeric($subject))) {
            echo "nice try :)";
            exit();
        }
    }

    $stmt = $db->prepare("INSERT INTO melding (spørsmål, emne_id, student_id, dato, tid) VALUES (:currentQuestion, :currentSubject, :currentStudentID, :currentDate, (NOW()))");

    $stmt->bindParam(':currentQuestion', $question);
    $stmt->bindParam(':currentSubject', $subject);
    $stmt->bindParam(':currentStudentID', $currentStudentId);
    $stmt->bindParam(':currentDate', $date);

    if ($stmt->execute()) {
        echo "<script>";
        echo "alert('Tilbakemeldingen din er mottatt!');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $cid = $_GET['cid'];
    //Sjekker om dropdown option er numeric eller ikke når foreleser bilder blir hentet
    if (!(is_numeric($cid))) {
        echo "nice try :)";
        exit();
    }

    echo getForeleserBilde($cid);
}

function getForeleserBilde($pin)
{
    $database = new Database();
    $db = $database->connect();
    $emneID = $pin;
    $a = array();

    $query = "SELECT bilde_navn FROM foreleser f 
    JOIN foreleser_emne fe on fe.foreleser_id = f.foreleser_id WHERE emne_id = '$emneID'";

    $stmt = $db->query($query);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($row) {
        foreach ($row as $row) {
            $bilde_navn = $row["bilde_navn"];
            array_push($a, $bilde_navn);
        }
    }


    return json_encode($a, JSON_PRETTY_PRINT);
}
