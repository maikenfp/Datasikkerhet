<?php
session_start();
include_once 'config/Database.php';

require __DIR__ . '/../../vendor/autoload.php';
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

if (isset($_POST['brukerEpost']) && isset($_POST['brukerPassord'])) {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $brukerEpost = validate($_POST['brukerEpost']);
    $brukerPassord = validate($_POST['brukerPassord']);

    if (empty($brukerEpost)) {
        header("Location: login_TEACHER.php?error=Du må skrive inn epost!");
        $logger->info("Ikke skrevet email under innlogging som foreleser");
        exit();
    } else if(empty($brukerPassord)) {
        header("Location: login_TEACHER.php?error=Du må skrive inn passord!");
        $logger->info("Ikke skrevet passord under innlogging som foreleser");
        exit();
    } else if (!filter_var($brukerEpost, FILTER_VALIDATE_EMAIL)) {
        header("Location: login_TEACHER.php?error=Eposten er ikke gyldig!");
        $logger->warning("Tastet inn ugyldig epost hos innlogging for foreleser");
        exit();
    } else {
        $sql = 'SELECT * FROM foreleser WHERE epost = :epost';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':epost', $brukerEpost, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            if(password_verify($brukerPassord, $row['passord'])) {
                //session_regenerate_id();

                $_SESSION['foreleser_id'] = $row['foreleser_id'];
                $_SESSION['navn'] = $row['navn'];
                $_SESSION['epost'] = $row['epost'];
                $_SESSION['bilde_navn'] = $row['bilde_navn'];

                header("Location: teacher.php");
                exit();
            } else {
                header("Location: login_TEACHER.php?error=Feil epost eller passord");
                $logger->warning("Feilet innlogging som foreleser");
                exit();
            }
        } else {
            header("Location: login_TEACHER.php?error=Feil epost eller passord");
            $logger->warning("Feilet innlogging som foreleser");
            exit();
        }

        $stmt->close();
    }
} else {
    header("Location: login_TEACHER.php");
    exit();
}
?>
