<?php

namespace JavanileDefine;

class Tokenizer extends \Nette\Tokenizer\Tokenizer
{
    public function __construct()
    {
        parent::__construct([
            'DEFINE' => 'define',
            'WITH' => 'with',
            'NUMBER' => '\d+',
            'WHITESPACE' => '\s+',
            'CONCEPT' => '\w+',
            'STRING' => '\w+',
            'COMMENT' => '\/\*(\*(?!\/)|[^*])*\*\/',
        ]);
    }
}