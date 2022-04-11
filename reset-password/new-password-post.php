<?php
    session_start();
    require '../config/Database.php';

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

    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    $minPassLen = 8;
    $maxPassLen = 32;

    $pw1 = validate(strip_tags($_POST['pw1']));
    $pw2 = validate(strip_tags($_POST['pw2']));

    if (strlen($pw2) < $minPassLen) {
        header("Location: new-password.php?error=Prøv et sikrere passord!");
        exit();
    } else if strlen($pw2) > $maxPassLen) {
        header("Location: new-password.php?error=Passordet er for langt!");
        exit();
    }

    $epost = $_SESSION['epost'];
    $pass_hash = password_hash($pw2, PASSWORD_DEFAULT);

    if($pw1 === $pw2){
        $database = new Database();
        $db = $database->connect();

        $query = "UPDATE foreleser SET passord='$pass_hash' WHERE epost='$epost'";
        $stmt = $db->query($query);

        echo "<script>";
        echo "alert('Passordet er tilbakestilt');";
        echo "</script>";
        echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
        $logger->notice("Bruker tilbakestilte sitt passord.");
    } else {
        header("Location: new-password.php?error=Passordene er ikke like!");
        $logger->notice("Tastet inn to ulike passord under glemt passord.");
    }

?>
