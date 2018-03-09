<?php
// send_03.php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Configuration:
$durable = false;
$exchangeName = 'exchange3';
$exchangeType = 'fanout';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName, $exchangeType, false, $durable, false);

$data = implode(' ', array_slice($argv, 1));
$data = $data ?: "Hello World!";
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, $exchangeName);

echo " [x] Sent: {$data}\n";

$channel->close();
$connection->close();

