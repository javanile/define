<?php

namespace JavanileDefine;

class Tokenizer extends \Nette\Tokenizer\Tokenizer
{
    public function __construct()
    {
        parent::__construct([

            'DEFINE' => 'define',
            'WITH' => 'with',

            "'{'" => '\{',
            "'}'" => '\}',
            "';'" => ';',

            'CONCEPT' => '\w+',

            'NUMBER' => '\d+',
            'WHITESPACE' => '\s+',
            'STRING' => '\w+',

            'COMMENT' => '\/\*(\*(?!\/)|[^*])*\*\/',
        ]);
    }
}