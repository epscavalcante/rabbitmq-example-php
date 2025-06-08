<?php

namespace Src\Connection1;

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../../vendor/autoload.php';

class Connection
{
    public function __construct() {}

    public function connect()
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
        sleep(10);
        echo "Canal encerrado com sucesso" . PHP_EOL;

        $channel->close();

        $connection->close();
    }
}

$connection = new Connection();

$connection->connect();
