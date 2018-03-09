<?php
// receive_03.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

// Configuration:
$durable = false;
$noAck = true;
$exchangeName = 'exchange3';
$exchangeType = 'fanout';
$consumerTag = '';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare($exchangeName, $exchangeType, false, $durable, false);

list($queueName, ,) = $channel->queue_declare("", false, false, true, false);

$channel->queue_bind($queueName, $exchangeName);

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo ' [x] ', $msg->body, "\n";
};

$channel->basic_consume($queueName, $consumerTag, false, $noAck, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

