<?php

namespace Javanile\Define;

class DefineParser extends GrammarParser
{
    /**
     *
     */
    protected $graph;

    /**
     *
     */
    protected $structure;

    /**
     * @var array
     */
    protected $definedConcepts;

    /**
     * @var array
     */
    protected $relatedConcepts;

    /**
     * @var array
     */
    protected $allRelatedConcepts;

    /**
     * @var array
     */
    protected $conceptInstructions;

    /**
     * @var
     */
    protected $currentFile;

    /**
     * @var
     */
    protected $currentLine;

    /**
     * DefineParser constructor.
     */
    public function __construct()
    {
        $this->definedConcepts = [];
        $this->relatedConcepts = [];
        $this->allRelatedConcepts = [];
        $this->conceptInstructions = [];
    }

    /**
     * @param $concept
     * @param array $with
     */
    public function define($concept, $with = [])
    {
        if (isset($this->definedConcepts[$concept])) {
            echo "ERROR: Concept '{$concept}' already defined at '{$this->definedConcepts[$concept]} ({$this->currentFile}:$this->currentLine)'.\n";
            exit(1);
        }

        $this->structure[$concept] = [

        ];

        /*
        var_dump([
            'concept' => $concept,
            'with' => $with,
            'instructions' => $instructions
        ]);
        die();
        */

        $this->definedConcepts[$concept] = $this->currentFile.':'.$this->currentLine;
        $this->relatedConcepts[$concept] = $with;
        //$this->allRelatedConcepts = array_merge($this->allRelatedConcepts, $with);

        $this->graph[$concept] = $with;
    }

    /**
     * @param $conceptList
     * @param $concept
     * @return mixed
     */
    public function append($conceptList, $concept)
    {
        var_dump($conceptList, $concept);

        $conceptList[] = [
            'concept' => $concept,
            'file' => $this->currentFile,
            'line' => $this->currentLine,
        ];

        return $conceptList;
    }

    /**
     * @return array
     */
    public function getNotRelatedConcepts()
    {
        return array_unique(array_diff(array_keys($this->definedConcepts), $this->allRelatedConcepts));
    }

    /**
     * @return array
     */
    public function getNotDefinedConcepts()
    {
        return array_unique(array_diff($this->allRelatedConcepts, array_keys($this->definedConcepts)));
    }

    /**
     * @return array
     */
    public function getDefinedConcepts()
    {
        return array_keys($this->definedConcepts);
    }

    /**
     * @param $concept
     * @return bool
     */
    public function isDefinedConcept($concept)
    {
        return isset($this->definedConcepts[$concept]);
    }

    /**
     * @param $requiredConcept
     * @param $fromConcept
     * @return bool
     */
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

    /**
     * @param $file
     */
    public function setCurrentFile($file)
    {
        $this->currentFile = $file;
    }

    /**
     * @param $line
     */
    public function setCurrentLine($line)
    {
        $this->currentLine = $line;
    }

    /**
     *
     */
    public function getGraph()
    {
        return $this->graph;
    }

    /**
     *
     */
    public function getStructure()
    {
        return $this->structure;
    }
}
