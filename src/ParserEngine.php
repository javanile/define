<?php

namespace Javanile\Define;

use Genesis\Lime\ParseEngine;

class ParserEngine extends ParseEngine
{
    /**
     *
     */
    public function __construct()
    {
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
     * @param $line
     */
    public function setCurrentLine($line)
    {
        $this->parser->setCurrentLine($line);
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
