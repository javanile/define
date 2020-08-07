<?php

namespace JavanileDefine;

class DefineParser extends GrammarParser
{
    protected $definedConcepts;
    protected $relatedConcepts;
    protected $conceptInstructions;

    public function __construct()
    {
        $this->definedConcepts = [];
        $this->relatedConcepts = [];
        $this->conceptInstructions = [];
    }

    public function define($concept, $with = [], $instructions = [])
    {
        $this->definedConcepts[] = $concept;
        $this->relatedConcepts = array_merge($this->relatedConcepts, $with);
        $this->conceptInstructions[$concept] = $instructions;
    }

    public function getNotRelatedConcepts()
    {
        return array_unique(array_diff($this->definedConcepts, $this->relatedConcepts));
    }

    public function getNotDefinedConcepts()
    {
        return array_unique(array_diff($this->relatedConcepts, $this->definedConcepts));
    }
}
