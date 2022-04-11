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

$logger->pushProcessor(function ($record) {
$record['extra']['user'] = get_current_user();
return $record;
});

$database = new Database();
$db = $database->connect();

$minPassLen = 8;
$maxPassLen = 32;
define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

if(isset($_POST["fore_reg"])) { // Requester action fra knappen som er til registering av studenter på index.php
    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $imgFile = $_FILES['picture']['name'];
    $tmp_dir = $_FILES['picture']['tmp_name'];
    $imgSize = $_FILES['picture']['size'];

    $username = validate(strip_tags($_POST["navn"]));
    $email = validate(strip_tags($_POST["epost"]));
    $password = validate(strip_tags($_POST["passord"]));
    $course = validate(strip_tags($_POST["studieretning"]));
    $sv1 = validate(strip_tags($_POST["sv1"]));
    $sv2 = validate(strip_tags($_POST["sv2"]));
    $sp1 = validate(strip_tags($_POST["sp1"]));
    $sp2 = validate(strip_tags($_POST["sp2"]));
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    $sv1_hash = password_hash($sv1, PASSWORD_DEFAULT);
    $sv2_hash = password_hash($sv2, PASSWORD_DEFAULT);

    if(empty($username)) {
        header("Location: foreleser.php?error=Du må skrive inn navn!");
        $logger->info("Skrev ikke inn navn under registrering av foreleser");
        exit();
    } else if(empty($email)) {
        header("Location: foreleser.php?error=Du må skrive inn epost!");
        $logger->info("Skrev ikke inn epost under registrering av foreleser");
        exit();
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: foreleser.php?error=Eposten er ikke gyldig!");
        $logger->notice("Skrev inn ugyldig epost under registrering av student");
        exit();
    } else if(empty($password)) {
        header("Location: foreleser.php?error=Du må skrive inn passord!");
        $logger->info("Skrev ikke inn passord under registrering av foreleser");
        exit();
    } else if(strlen($password) < $minPassLen) { //Check if pasword is shorter than value of $passlen
        header("Location: foreleser.php?error=Prøv et sikrere passord");
        exit();
    } else if(strlen($password) > $maxPassLen) { //Check if pasword is longer than value of $passlen
        header("Location: foreleser.php?error=Prøv et mindre sikkert passord ;)");
        exit();
    } else if(empty($course)) {
        header("Location: foreleser.php?error=Du må skrive inn emne!");
        $logger->info("Valgte ikke emne under registrering av foreleser");
        exit();
    } else if(empty($imgFile)) {
        header("Location: foreleser.php?error=Du har ikke valgt bilde!");
        $logger->info("La ikke ved et bilde under registrering av foreleser");
        exit();
    } else if($sp1 === $sp2) {
        header("Location: foreleser.php?error=Du kan ikke velge samme sikkerhetsspørsmål!");
        exit();
    } else {
        $query = "SELECT epost FROM foreleser WHERE epost = '$email'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $row_count = $stmt->fetch();

        if($row_count > 0) {
            header("Location: foreleser.php?error=Eposten er allerede i bruk!");
            $logger->info("Prøvde å registrere en foreleser bruker med en eksisterende epost");
            exit();
        } else {
            $sql= "INSERT INTO foreleser (navn,epost,passord,glemt_question_1,glemt_svar_1,glemt_question_2,glemt_svar_2,bilde_navn)
            VALUES (:uname,:uemail,:upassord, (SELECT question FROM question WHERE question_id = :sp1), :sv1,
            (SELECT question FROM question WHERE question_id =:sp2), :sv2, :bilde_navn)";

            $upload_dir = '../photos/';

            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION));

            $valid_extensions = array('jpeg', 'jpg', 'png');
            $coverpic = rand(1000,1000000).".".$imgExt;

            if(in_array($imgExt, $valid_extensions)){
                if($imgSize < 8*MB){
                    move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
                    echo "Opplasting ferdig";
                } else{
                    header("Location: foreleser.php?error=Filen er for stor");
                    var_dump($imgSize);
                    return;
                    $logger->warning("Prøvde å sende inn for stor fil!");
                    exit();
                }
            } else{
                header("Location: foreleser.php?error=Det er ikke gyldig filtype!");
                $logger->warning("Prøvde å sende inn en uønsket filtype!");
                exit();
            }

            $pic = $coverpic;

            $insert_stmt = $db->prepare($sql);
            $insert_stmt->bindParam(":uname", $username);
            $insert_stmt->bindParam(":uemail", $email);
            $insert_stmt->bindParam(":upassord", $pass_hash);
            $insert_stmt->bindParam(":sp1", $sp1);
            $insert_stmt->bindParam(":sv1", $sv1_hash);
            $insert_stmt->bindParam(":sp2", $sp2);
            $insert_stmt->bindParam(":sv2", $sv2_hash);
            $insert_stmt->bindParam(":bilde_navn", $pic);

            $insert_stmt->execute(array(
                ":uname" => $username,
                ":uemail" => $email,
                ":upassord" => $pass_hash,
                ":sp1" => $sp1,
                ":sv1" => $sv1_hash,
                ":sp2" => $sp2,
                ":sv2" => $sv2_hash,
                ":bilde_navn" => $pic));

                $_SESSION['foreleser_id'] = $db->lastInsertId();
                $_SESSION['epost'] = $email;
                $_SESSION['navn'] = $username;
                $_SESSION['bilde_navn'] = $pic;

                $id = $db->lastInsertId();

                $stmt2 = $db->prepare("INSERT INTO foreleser_emne (foreleser_id, emne_id) VALUES ('$id', '$course')");
                $stmt2->execute();

                header("Location: ../teacher.php");
                $logger->info("Foreleser bruker opprettet");
                exit();
            }

        }
}
