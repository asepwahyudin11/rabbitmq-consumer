<?php 

    require_once '../vendor/autoload.php';
    use PhpAmqpLib\Connection\AMQPStreamConnection;

    $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    echo " [*] Waiting for messages. To exit press CTRL+C\n";

    $callback = function ($msg) {
        echo ' [x] Received ', $msg->body, "\n";
        sleep(substr_count($msg->body, '.'));
        echo " [x] Done\n";
        $msg->ack();
    };
      
    $channel->basic_consume('hello', '', false, false, false, false, $callback);
    
    while ($channel->is_open()) {
        $channel->wait();
    }
?>