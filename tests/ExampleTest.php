<?php

use Src\Example;

test('example', function () {
    $example = new Example;
    expect($example->sayHello())->toBe('Hello');
});
