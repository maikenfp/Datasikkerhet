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

    $logger->pushProcessor(function ($record) {
    $record['extra']['user'] = get_current_user();
    return $record;
    });

    $pw1 = $_POST['pw1'];
    $pw2 = $_POST['pw2'];

    $epost = $_SESSION['epost'];

    if($pw1 === $pw2){
        $database = new Database();
        $db = $database->connect();

        $query = "UPDATE foreleser SET passord='$pw2' WHERE epost='$epost'";
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
