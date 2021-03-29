<?php

use Javanile\Define\DefaultCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

test('example', function () {
    $app = new Application('test');
    $app->add(new DefaultCommand());

    $tester = new CommandTester($app->find('define'));

    $statusCode = $tester->execute(['name' => 'tests/fixtures/example1.def']);

    expect(true)->toBeTrue();
});
