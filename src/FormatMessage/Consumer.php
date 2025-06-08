<?php

namespace Src\ProducerConsumer;

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    public function __construct() {}

    public function consume()
    {
        $connection = new AMQPStreamConnection(
            host: 'rabbitmq',
            user: 'root',
            password: 'root',
            port: 5672,
            vhost: '/'
        );

        $channel = $connection->channel();


        $queue = 'format_message_queue';
        $exchange = 'format_message_exchange';

        $channel->queue_declare(
            queue: $queue,
            passive: false,
            durable: true,
            auto_delete: false
        );

        $channel->exchange_declare(
            exchange: $exchange,
            type: AMQPExchangeType::DIRECT,
            passive: false,
            durable: true,
            auto_delete: false
        );

        $channel->queue_bind(
            queue: $queue,
            exchange: $exchange
        );

        $channel->basic_consume(
            queue: $queue,
            consumer_tag: '',
            no_local: false,
            no_ack: false,
            exclusive: false,
            nowait: false,
            callback: function (AMQPMessage $message) {
                $properties = $message->get_properties();

                var_dump([
                    'body' => $message->getBody(),
                    'properties' => $message->get_properties()
                ]);

                if ($properties['content_type'] ?? false) {
                    echo "Tipo Json decofificado" . PHP_EOL;
                    $data = json_decode($message->getBody(), true);
                    var_dump($data);
                }

                echo "Mensagem consumida com sucesso" . PHP_EOL;
            }

        );

        $channel->consume();
        //$channel->close();

        //$connection->close();
    }
}

$consumer = new Consumer();
$consumer->consume();
