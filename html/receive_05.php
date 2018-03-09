<?php
// receive_05.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// Configuration:
$durable = false;
$noAck = true;
$exchangeName = 'exchange5';
$exchangeType = 'topic';
$consumerTag = '';
$bindingKey = 'undefined';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName, $exchangeType, false, $durable, false);

list($queueName, ,) = $channel->queue_declare("", false, false, true, false);

$bindingKeys = array_slice($argv, 1);
if(empty($bindingKeys)) {
	file_put_contents('php://stderr', "Usage: $argv[0] [binding_key]\n");
	exit(1);
}

foreach($bindingKeys as $bindingKey) {
    $channel->queue_bind($queueName, $exchangeName, $bindingKey);
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

