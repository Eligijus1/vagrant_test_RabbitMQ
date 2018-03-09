<?php
// send_02.php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Configuration:
$queue = 'test_queue2';
$durable = true;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare($queue, false, $durable, false, false);

$data = implode(' ', array_slice($argv, 1));
$data = ($data ?: "Hello World!");

$msg = new AMQPMessage($data, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

$channel->basic_publish($msg, '', $queue);

echo " [x] Sent: {$data}\n";

$channel->close();
$connection->close();

