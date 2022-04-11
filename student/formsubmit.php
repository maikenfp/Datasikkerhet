<?php
// Start the session
session_start();
require '.././config/Database.php';

require __DIR__ . '/../../../vendor/autoload.php';


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\GelfHandler;
use Gelf\Message;
use Monolog\Formatter\GelfMessageFormatter;

$logger = new Logger('sikkerhet');
$transport = new Gelf\Transport\UdpTransport("127.0.0.1", 12201);
$publisher = new Gelf\Publisher($transport);
$handler = new GelfHandler($publisher, Logger::DEBUG);
$logger->pushHandler($handler);

/*$logger->pushProcessor(function ($record) {
$record['extra']['user'] = get_current_user();
return $record;
});*/
$database = new Database();
// $db = $database->connect();
$db = $database->connectStudent();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Form variabler (htmlspecialchars hindrer XSS) 
    $currentStudentId = $_SESSION["student_id"];
    $question = htmlspecialchars($_POST['subjectQuestion']);
    //$date = date('Y-m-d');

    //Sjekker om dropdown option er numeric eller ikke (hindrer sql injection og XSS i inspect element)
    if (isset($_POST['subject'])) {
        htmlspecialchars($subject = $_POST['subject']);

        if (!(is_numeric($subject))) {
            echo "nice try :)";
            $logger->warning("Numeric dropdown hos student");
            exit();
        }
    }
    $stmt = $db->prepare("INSERT INTO melding (question, emne_id, student_id) VALUES (:currentQuestion, :currentSubject, :currentStudentID)");

    $stmt->bindParam(':currentQuestion', $question);
    $stmt->bindParam(':currentSubject', $subject);
    $stmt->bindParam(':currentStudentID', $currentStudentId);

    if ($stmt->execute()) {
        echo "<script>";
        echo "alert('Tilbakemeldingen din er mottatt!');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=index.php'>";
        $logger->info("Student sendte inn en melding");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $cid = $_GET['cid'];
    //Sjekker om dropdown option er numeric eller ikke når foreleser bilder blir hentet
    if (!(is_numeric($cid))) {
        echo "nice try :)";
        $logger->warning("Numeric dropdown hos student");
        exit();
    }

    echo getForeleserBilde($cid);
}

function getForeleserBilde($pin)
{
    $database = new Database();
    // $db = $database->connect();
    $db = $database->connectStudent();
    $emneID = $pin;
    $a = array();

    $stmt = $db->prepare("SELECT bilde_navn FROM foreleser_bilde WHERE emne_id = :id");
    $stmt->execute(['id' => $emneID]);

    while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
        foreach ($row as $row) {
            $bilde_navn = $row["bilde_navn"];
            array_push($a, $bilde_navn);
        }
    }
    // $stmt = $db->query($query);
    // $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // if ($row) {
    //     foreach ($row as $row) {
    //         $bilde_navn = $row["bilde_navn"];
    //         array_push($a, $bilde_navn);
    //     }
    // }


    return json_encode($a, JSON_PRETTY_PRINT);
}
