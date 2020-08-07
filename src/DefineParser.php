<?php

namespace JavanileDefine;

class DefineParser extends GrammarParser
{
    protected $definedConcepts;
    protected $relatedConcepts;

    public function __construct()
    {
        $this->definedConcepts = [];
        $this->relatedConcepts = [];
    }

    public function define($concept, $with = [])
    {
        $this->definedConcepts[] = $concept;
        $this->relatedConcepts = array_merge($this->relatedConcepts, $with);
    }

    public function getNotRelatedConcepts()
    {
        return array_unique(array_diff($this->definedConcepts, $this->relatedConcepts));
    }
}
