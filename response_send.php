<?php

session_start();
require 'config/Database.php';

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
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $responseID = strip_tags($_POST['responseID']);
  $reply = strip_tags($_POST['reply']);

  $sql = "UPDATE melding SET svar='$reply' WHERE melding_id='$responseID'";
  $result = $db->query($sql);
  $logger->info("Foreleser svarte på en melding");

  if($result) {
    echo "<meta http-equiv='refresh' content='0;url=teachersubject.php'>";
  }

}
?>
