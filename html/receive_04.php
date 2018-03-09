<?php
// receive_04.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// Configuration:
$durable = false;
$noAck = true;
$exchangeName = 'exchange4';
$exchangeType = 'direct';
$consumerTag = '';
$routingKey = 'undefined';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName, $exchangeType, false, $durable, false);

list($queueName, ,) = $channel->queue_declare("", false, false, true, false);

$severities = array_slice($argv, 1);
if(empty($severities )) {
	file_put_contents('php://stderr', "Usage: $argv[0] [info] [warning] [error]\n");
	exit(1);
}

foreach($severities as $routingKey) {
    $channel->queue_bind($queueName, $exchangeName, $routingKey);
}

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo ' [x] '.$msg->delivery_info['routing_key'].':'.$msg->body."\n";
};

$channel->basic_consume($queueName, $consumerTag, false, $noAck, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

