<?php

namespace Src\ProducerConsumer;

require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    public function __construct() {}

    public function produce()
    {
        $connection = new AMQPStreamConnection(
            host: 'rabbitmq',
            user: 'root',
            password: 'root',
            port: 5672,
            vhost: '/'
        );

        $channel = $connection->channel();

        echo "Canal criado com sucesso" . PHP_EOL;

        $queue = 'publisher_consumer_queue';
        $exchange = 'publisher_consumer_exchange';

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
            exchange: $exchange,
        );

        $textMessage = new AMQPMessage(
            body: 'Hello world',
            properties: [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
            ]
        );

        $channel->basic_publish(
            msg: $textMessage,
            exchange: $exchange,
        );


        echo "Mensagem publicada com sucesso" . PHP_EOL;

        $channel->close();

        $connection->close();
    }
}

$producer = new Producer();
$producer->produce();
