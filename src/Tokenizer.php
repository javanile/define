<?php

namespace Javanile\Define;

class Tokenizer extends \Nette\Tokenizer\Tokenizer
{
    public function __construct()
    {
        parent::__construct([

            'DEFINE' => 'define',
            'WITH' => 'with',

            "','" => ',',

            'LITERAL' => '\w+',
            'PATH' => '\/[\w\/_-]+',

            'NUMBER' => '\d+',
            'WHITESPACE' => '\s+',
            'STRING' => '\w+',

            'COMMENT' => '\/\*(\*(?!\/)|[^*])*\*\/',
        ]);
    }
}
