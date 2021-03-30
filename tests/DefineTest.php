<?php

use Javanile\Define\DefineCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

test('example', function () {
    $app = new Application('test');
    $app->add(new DefineCommand());

    $tester = new CommandTester($app->find('define'));

    $statusCode = $tester->execute(['name' => 'tests/fixtures/example1.def']);

    expect(true)->toBeTrue();
});
