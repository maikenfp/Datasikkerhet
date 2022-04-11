<?php
session_start();
include "../config/Database.php";

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

$passlen = 8;
$maxPassLen = 32;

if(isset($_POST["stud_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php

    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $username = validate(strip_tags($_POST["navn"])); // Tar infoen som står i boksene med tags som er i ["tag"].
    $email = validate(strip_tags($_POST["epost"]));
    $password = validate(strip_tags($_POST["passord"]));
    $course = validate(strip_tags($_POST["studieretning"]));
    $year = validate(strip_tags($_POST["studiekull"]));
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);

    if(empty($username)) {
        header("Location: student.php?error=Du må skrive inn navn!");
        $logger->info("Skrev ikke inn navn under registrering av student");
        exit();
    } else if(empty($email)) {
        header("Location: student.php?error=Du må skrive inn epost!");
        $logger->info("Skrev ikke inn epost under registrering av student");
        exit();
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: student.php?error=Eposten er ikke gyldig!");
        $logger->notice("Skrev inn ugyldig epost under registrering av student");
        exit();
    } else if(empty($password)) {
        header("Location: student.php?error=Du må skrive inn passord!");
        $logger->info("Skrev ikke inn passord under registrering av student");
        exit();
    } else if(strlen($password) < $passlen) { //Check if pasword is shorter than value of $passlen
        header("Location: student.php?error=Prøv et sikrere passord");
        exit();
    } else if(strlen($password) > $maxPassLen) { //Check if pasword is longer than value of $passlen
        header("Location: foreleser.php?error=Prøv et mindre sikkert passord ;)");
        exit();
    } else if(empty($course)) {
        header("Location: student.php?error=Du må skrive inn studeretning!");
        $logger->info("Valgte ikke studieretning under registrering av student");
        exit();
    } else if(empty($year)) {
        header("Location: student.php?error=Du må skrive inn studiekull!");
        $logger->info("Valgte ikke studiekull under registrering av student");
        exit();
    }

    else {
        $query = "SELECT epost FROM student WHERE epost = '$email'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row_count = $stmt->fetch();

        if($row_count > 0) {
            header("Location: student.php?error=Eposten er allerede i bruk!");
            $logger->info("Prøvde å registrere en student bruker med en eksisterende epost");
            exit();
        }

        else{
            $sql = "INSERT INTO student (navn, epost, passord, studieretning, studiekull)
            VALUES (:uname, :uemail, :upassord, :ustudieretning, :ustudiekull)";

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->bindParam(":uname", $username);
            $insert_stmt->bindParam(":uemail", $email);
            $insert_stmt->bindParam(":upassord", $pass_hash);
            $insert_stmt->bindParam(":ustudieretning", $course);
            $insert_stmt->bindParam(":ustudiekull", $year);

            $insert_stmt->execute(array(
                    ":uname" => $username,
                    ":uemail" => $email,
                    ":upassord" => $pass_hash,
                    ":ustudieretning" => $course,
                    ":ustudiekull" => $year));

            $_SESSION['student_id'] = $db->lastInsertId();
            $_SESSION['studieretning'] = $course;
            $_SESSION['studiekull'] = $year;
            $_SESSION['epost'] = $email;
            $_SESSION['navn'] = $username;

            $id = $db->lastInsertId();

            header("Location: ../student/index.php");
            $logger->info("Student bruker opprettet");
            exit();
        }
    }
}
