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
    
    function validate($data) {
        $data = preg_replace('/[^A-Za-z0-9@. ]/i', '', $data);
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);

        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $epost = $_POST['epost'];
         $_SESSION['epost'] = $epost;
    }
    
    $epost = validate($_SESSION['epost']);

    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * from foreleser WHERE epost='$epost'";
    $stmt = $db->query($query);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(($epost === $row['epost'])){
        $_SESSION['epost'] = validate($epost));

    }else{
        validate(header("Location: index.php?error=Email ikke registrert!"));
        $logger->notice("Brukte en ikke-eksisterende epost under glemt passord.");
    }

?>

<!DOCTYPE html>
<html lang="nb">
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta charset="UTF-8" />
        <title>Reset Passord</title>
        <!--force php to load css-->
        <link rel="stylesheet" href="./style.css?v=<?php echo time(); ?>">

    </head>
    <body> 
        <main>
            <h1>Foreleser</h1>
            <?php if (isset($_GET['error'])) { ?>
	            <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <form action="new-password.php" method="post" class="form">
                    <label for="svar1"><?php
                        echo validate($row['glemt_question_1']);
                    ?></label>
                    <input type="text" name="svar1"/>
                    <label for="svar2"><?php
                        echo validate($row['glemt_question_2']);
                    ?></label>
                    <input type="text" name="svar2"/>
                    <button type="submit" name="submit">Go</button>
            </form>
        </main>
    </body>
</html>

