<?php

namespace Javanile\Define;

class DefineParser extends GrammarParser
{
    /**
     *
     */
    protected $concepts;

    /**
     *
     */
    protected $relatedConcepts;

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
        $this->concepts = [];
        $this->relatedConcepts = [];
    }

    /**
     * @param $concept
     * @param array $with
     */
    public function define($concept)
    {
        if (isset($this->concepts[$concept])) {
            echo "ERROR: Concept '{$concept}' already defined at '{$this->concepts[$concept]['definedAt']} ({$this->currentFile}:$this->currentLine)'.\n";
            exit(1);
        }

        $this->concepts[$concept] = [
            'definedAt' => $this->currentFile . ':' . $this->currentLine,
        ];
    }

    /**
     * @param $concept
     * @param $conceptList
     */
    public function relate($concept, $conceptList)
    {
        $this->concepts[$concept]['with'] = $conceptList;
        foreach ($conceptList as $relatedConcept => $info) {
            $info['relatedWith'] = $concept;
            $this->relatedConcepts[$concept][] = $info['relatedWith'];
        }
    }

    /**
     * @param $conceptList
     * @param $concept
     * @return mixed
     */
    public function append($conceptList, $concept)
    {
        if (isset($conceptList[$concept])) {
            echo "ERROR: Concept '{$concept}' duplicate at '{$conceptList[$concept]['relatedAt']} ({$this->currentFile}:$this->currentLine)'.\n";
            exit(1);
        }

        $conceptList[$concept] = [
            'relatedAt' => $this->currentFile.':'.$this->currentLine,
        ];

        return $conceptList;
    }

    /**
     * @return array
     */
    public function getNotRelatedConcepts()
    {
        return array_diff(array_keys($this->concepts), array_keys($this->relatedConcepts));
    }

    /**
     * @return array
     */
    public function getNotDefinedConcepts()
    {
        return array_diff(array_keys($this->relatedConcepts), array_keys($this->concepts));
    }

    /**
     * @return array
     */
    public function getDefinedConcepts()
    {
        return array_keys($this->concepts);
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
        return isset($this->concepts[$concept]);
    }

    /**
     *
     *
     * @param $requiredConcept
     * @param $fromConcept
     *
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
        return $this->concepts;
    }

    /**
     *
     */
    public function getStructure()
    {
        return $this->concepts;
    }
}
