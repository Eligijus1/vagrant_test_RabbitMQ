<?php
// send_01.php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

// Configuration:
$queue = 'hello';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare($queue, false, false, false, false);

$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, '', $queue);

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

