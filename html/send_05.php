<?php
// send_05.php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Configuration:
$durable = false;
$exchangeName = 'exchange5';
$exchangeType = 'topic';
$routingKey = 'undefined';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName, $exchangeType, false, $durable, false);

$routingKey = isset($argv[1]) && !empty($argv[1]) ? $argv[1] : 'anonymous.info';

$data = implode(' ', array_slice($argv, 2));
$data = $data ?: "Hello World!";
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, $exchangeName, $routingKey);

echo " [x] Sent ",$routingKey,':',$data," \n";

$channel->close();
$connection->close();

