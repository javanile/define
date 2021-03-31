<?php

namespace Javanile\Define;

use Genesis\Lime\ParseEngine;

class ParserEngine extends ParseEngine
{
    /**
     * Main tokenizer.
     */
    protected $tokenizer;

    /**
     *
     */
    public function __construct()
    {
        $this->tokenizer = new Tokenizer();

        parent::__construct(new DefineParser());
    }

    /**
     * @param $file
     */
    public function setCurrentFile($file)
    {
        $this->parser->setCurrentFile($file);
    }

    /**
     *
     * @param $code
     * @throws \Nette\Tokenizer\Exception
     */
    public function parse($code, $file = 'null', &$line = 1)
    {
        $this->reset();
        $this->setCurrentFile($file);
        $this->setCurrentLine($line);
        $stream = $this->tokenizer->tokenize($code);
        foreach ($stream->tokens as $token) {
            if ($this->debug) {
                echo "#[{$token->type} {$file}:] ".json_encode($token->value)."\n";
            }
            if ($token->type == 'COMMENT' || $token->type == 'WHITESPACE') {
                $this->setCurrentLine($line);
                $line += substr_count($token->value, "\n");
                continue;
            }
            $this->eat($token->type, $token->value);
        }
        $result = $this->eat_eof();
    }

    /**
     * @param $line
     */
    public function setCurrentLine($line)
    {
        $this->parser->setCurrentLine($line);
    }

    /**
     * Check if concept is defined.
     *
     * @param $concept
     *
     * @return bool
     */
    public function isConceptDefined($concept)
    {
        return $this->parser->isConceptDefined($concept);
    }

    /**
     * Check if concept is defined.
     *
     * @param $concept
     *
     * @return bool
     */
    public function getNotDefinedConcepts()
    {
        return $this->parser->getNotDefinedConcepts();
    }

    /**
     *
     */
    public function getGraph()
    {
        return $this->parser->getGraph();
    }

    /**
     *
     */
    public function getStructure()
    {
        return $this->parser->getStructure();
    }
}
