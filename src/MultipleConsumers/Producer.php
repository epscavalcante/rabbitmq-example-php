<?php

namespace Src\MultipleConsumers;

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

        $queue = 'multiple_consumer_queue';
        $exchange = 'multiple_consumer_exchange';

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


        for ($i = 1; $i <= 100; $i++) {
            $jsonMessage = new AMQPMessage(
                body: json_encode([
                    'id' => uniqid('PROD_'),
                    'name' => $i . ' Nome do produto'
                ]),
                properties: [
                    'content_type' => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]
            );

            $channel->basic_publish(
                msg: $jsonMessage,
                exchange: $exchange,
            );

            echo "Mensagem publicada com sucesso" . PHP_EOL;
        }

        $channel->close();

        $connection->close();
    }
}

$producer = new Producer();
$producer->produce();
