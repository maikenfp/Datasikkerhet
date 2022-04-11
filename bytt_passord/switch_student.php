<?php

session_start();
include '../config/Database.php';

require __DIR__ . '/../../../vendor/autoload.php';
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\GelfHandler;
use Gelf\Message;
use Monolog\Formatter\GelfMessageFormatter;

$logger = new Logger('sikkerhet');
$transport = new Gelf\Transport\UdpTransport("127.0.0.1", 12201);
$publisher = new Gelf\Publisher($transport);
$handler = new GelfHandler($publisher,Logger::DEBUG);
$logger->pushHandler($handler);

/*$logger->pushProcessor(function ($record) {
$record['extra']['user'] = get_current_user();
return $record;
});*/

$database = new Database();
$db = $database->connect();

$sid = $_SESSION['student_id'];
$minPassLen = 8;
$maxPassLen = 32;

if(empty($sid)){
    header('Location: ../index.php');
} else {
    if(isset($_POST['passord']) && isset($_POST['nyttpassord'])){
        function validate($data){
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        $passord = validate($_POST['passord']);
        $nyttpassord = validate($_POST['nyttpassord']);
        $nyttpassord_hash = password_hash($nyttpassord, PASSWORD_DEFAULT);

        if (empty($passord)) {
            header("Location: change_student.php?error=Du må skrive inn passord!");
            $logger->info("Skrev ikke inn gammelt passord!");
            exit();
        } else if(empty($nyttpassord)){
            header("Location: change_student.php?error=Du må skrive inn nytt passord!");
            $logger->info("Skrev ikke inn nytt passord!");
            exit();
        } else if(strlen($nyttpassord) < $minPassLen) {
            header("Location: change_student.php?error=Prøv et sikrere passord!");
            exit();
        } else if(strlen($nyttpassord) > $maxPassLen) {
            header("Location: change_student.php?error=Passordet er for langt!");
            exit();
        } else {
            $sql = "SELECT passord, student_id FROM student WHERE student_id='$sid'";
            $stmt= $db->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($row){
                if(intval($row['student_id']) == $sid && password_verify($passord, $row['passord'])){
                    $query = "UPDATE student SET passord='$nyttpassord_hash' WHERE student_id='$sid'";
                    $change = $db->query($query);

                    echo "<script>";
                    echo "alert('Passordet er endret!');";
                    echo "</script>";
                    echo "<meta http-equiv='refresh' content='0;url=../student/index.php'>";
                    exit();
                } else{
                    header("Location: change_student.php?error=Feil passord");
                    $logger->warning("Tastet inn feil passord!");
                    exit();
                }
            } else{
                header("Location: change_student.php?error=Feil passord");
                $logger->warning("Tastet inn feil passord!");
                exit();
            }
        }
    } else {
            header("Location: ../student/index.php");
            echo "<script>";
            echo "alert('Noe gikk galt');";
            echo "</script>";
            echo "<meta http-equiv='refresh' content='0;url=../student/index.php'>";
            $logger->debug("Noe gikk galt under bytting av passord for student");
            exit();

    }
}
?>