<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

test('example', function () {
    $connection = new AMQPStreamConnection(
        host: 'rabbitmq',
        user: 'root',
        password: 'root',
        port: 5672,
        vhost: '/'
    );

    $channel = $connection->channel();

    echo "Canal criado com sucesso";
    sleep(50);
    echo "Canal encerrado com sucesso";

    $channel->close();

    $connection->close();
});
