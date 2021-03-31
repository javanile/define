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
