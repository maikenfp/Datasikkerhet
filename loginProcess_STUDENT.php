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

/*$logger->pushProcessor(function ($record) {
    $record['extra']['user'] = get_current_user();
    return $record;
});*/

$database = new Database();
// $db = $database->connect();
$db = $database->connectStudent();

if (isset($_POST['brukerEpost']) && isset($_POST['brukerPassord'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $brukerEpost = validate($_POST['brukerEpost']);
    $brukerPassord = validate($_POST['brukerPassord']);

    if (empty($brukerEpost)) {
        htmlspecialchars(header("Location: login_STUDENT.php?error=Du må skrive inn epost!"));
        /*$logger->pushProcessor(function ($record) {
            $record['extra']['email'] = $brukerEpost;
            return $record;
        });*/
        $logger->info('Ikke skrevet email under innlogging som student');
        //$logger->info('Ikke skrevet email under innlogging som student', ['email' => '$brukerEpost']);

        exit();
    } else if(empty($brukerPassord)) {
        htmlspecialchars(header("Location: login_STUDENT.php?error=Du må skrive inn passord!"));
        $logger->info("Ikke skrevet passord under innlogging som student");
        exit();
    } else if (!filter_var($brukerEpost, FILTER_VALIDATE_EMAIL)) {
        htmlspecialchars(header("Location: login_STUDENT.php?error=Eposten er ikke gyldig!"));
        $logger->warning("Tastet inn ugyldig epost hos innlogging for student");
        exit();
    } else{
        $sql = 'SELECT * FROM student WHERE epost = :epost';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':epost', $brukerEpost, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            if(password_verify($brukerPassord, $row['passord'])) {
                session_regenerate_id();

                $_SESSION['student_id'] = $row['student_id'];
                $_SESSION['navn'] = $row['navn'];
                $_SESSION['epost'] = $row['epost'];
                $_SESSION['studieretning'] = $row['studieretning'];
                $_SESSION['studiekull'] = $row['studiekull'];

                header("Location: student/index.php");
                /*$logger->pushProcessor(function ($record) {
                    $record['extra']['user'] = $brukerEpost;
                    return $record;
                });*/
                $logger->info("Student logget inn");
                exit();
            } else {
                htmlspecialchars(header("Location: login_STUDENT.php?error=Feil epost eller passord"));
                $logger->warning("Feilet innlogging som student");
                exit();
            }
        } else {
            htmlspecialchars(header("Location: login_STUDENT.php?error=Feil epost eller passord"));
            $logger->warning("Feilet innlogging som student");
            exit();
        }

        $stmt->close();
    }

} else {
    header("Location: login_STUDENT.php");
    exit();
}
?>
