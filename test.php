<?php

require __DIR__ . '/../../vendor/autoload.php';
include 'loginProcess_STUDENT.php';

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

/*$logger->info('hei');/*
$logger->warning('First message',['system' =>'testmodul', 'user' => get_current_user()]);
$logger->info('Andre info');
$logger->error('noe gikk skikkelig dritt!');
$logger->info('Tredje info');*/
?>
