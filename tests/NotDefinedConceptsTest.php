<?php

use Javanile\Define\ParserEngine;

test('not defined concepts', function () {
    $parser = new ParserEngine();
    $parser->parse('define A with B C');
    expect($parser->getNotDefinedConcepts())->toEqual(['B', 'C']);
});
