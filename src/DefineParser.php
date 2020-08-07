<?php

namespace JavanileDefine;

class DefineParser extends GrammarParser
{
    protected $definedConcepts;
    protected $relatedConcepts;
    protected $allRelatedConcepts;
    protected $conceptInstructions;
    protected $currentFile;
    protected $currentLine;

    public function __construct()
    {
        $this->definedConcepts = [];
        $this->relatedConcepts = [];
        $this->allRelatedConcepts = [];
        $this->conceptInstructions = [];
    }

    public function define($concept, $with = [], $instructions = [])
    {
        /*
        var_dump([
            'concept' => $concept,
            'with' => $with,
            'instructions' => $instructions
        ]);
        die();
        */

        if (isset($this->definedConcepts[$concept])) {
            echo "ERROR: Concept '{$concept}' already defined at '{$this->definedConcepts[$concept]} ({$this->currentFile}:$this->currentLine)'.\n";
            exit(1);
        }
        $this->definedConcepts[$concept] = $this->currentFile.':'.$this->currentLine;
        $this->relatedConcepts[$concept] = $with;
        $this->allRelatedConcepts = array_merge($this->allRelatedConcepts, $with);
        $this->conceptInstructions[$concept] = $instructions;
    }

    public function getNotRelatedConcepts()
    {
        return array_unique(array_diff(array_keys($this->definedConcepts), $this->allRelatedConcepts));
    }

    public function getNotDefinedConcepts()
    {
        return array_unique(array_diff($this->allRelatedConcepts, array_keys($this->definedConcepts)));
    }

    public function getDefinedConcepts()
    {
        return array_keys($this->definedConcepts);
    }

    public function getConceptInstructions($concept)
    {
        return $this->conceptInstructions[$concept];
    }

    public function isDefinedConcept($concept)
    {
        return isset($this->definedConcepts[$concept]);
    }

    public function discoverConcept($requiredConcept, $fromConcept)
    {
        if ($requiredConcept == $fromConcept) {
            return true;
        }

        foreach ($this->relatedConcepts[$fromConcept] as $relatedConcept) {
            $discovered = $this->discoverConcept($requiredConcept, $relatedConcept);
            if ($discovered) {
                return true;
            }
        }

        return false;
    }

    public function setCurrentFile($file)
    {
        $this->currentFile = $file;
    }

    public function setCurrentLine($line)
    {
        $this->currentLine = $line;
    }
}
