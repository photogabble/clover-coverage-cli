<?php

namespace Photogabble\CloverCoverage;

class Analysis
{
    /**
     * @var Analysis[]
     */
    public $files = [];
    public $loc = 0;
    public $ncLoc = 0;
    public $classes = 0;
    public $methods = 0;
    public $coveredMethods = 0;
    public $conditionals = 0;
    public $coveredConditionals = 0;
    public $statements = 0;
    public $coveredStatements = 0;
    public $elements = 0;
    public $coveredElements = 0;

    /**
     * Analysis constructor.
     *
     * @param int $loc
     * @param int $ncLoc
     * @param int $classes
     * @param int $methods
     * @param int $coveredMethods
     * @param int $conditionals
     * @param int $coveredConditionals
     * @param int $statements
     * @param int $coveredStatements
     * @param int $elements
     * @param int $coveredElements
     */
    public function __construct($loc = 0, $ncLoc = 0, $classes = 0, $methods = 0, $coveredMethods = 0, $conditionals = 0, $coveredConditionals = 0, $statements = 0, $coveredStatements = 0, $elements = 0, $coveredElements = 0)
    {
        $this->loc = $loc;
        $this->ncLoc = $ncLoc;
        $this->classes = $classes;
        $this->methods = $methods;
        $this->coveredMethods = $coveredMethods;
        $this->conditionals = $conditionals;
        $this->coveredConditionals = $coveredConditionals;
        $this->statements = $statements;
        $this->coveredStatements = $coveredStatements;
        $this->elements = $elements;
        $this->coveredElements = $coveredElements;
    }

    /**
     * @param string $name
     * @param Analysis $file
     */
    public function addFile($name, Analysis $file)
    {
        $this->files[$name] = $file;

        $this->loc += $file->loc;
        $this->ncLoc += $file->ncLoc;

        $this->classes += $file->classes;

        $this->methods += $file->methods;
        $this->coveredMethods += $file->coveredMethods;

        $this->conditionals += $file->conditionals;
        $this->coveredConditionals += $file->coveredConditionals;

        $this->statements += $file->statements;
        $this->coveredStatements += $file->coveredStatements;

        $this->elements += $file->elements;
        $this->coveredElements += $file->coveredElements;
    }

    /**
     * Get coverage percentage.
     *
     * @return float
     */
    public function getCoveragePercentage()
    {
        return ($this->elements + $this->statements + $this->conditionals + $this->methods === 0) ? 0 : (($this->coveredElements + $this->coveredStatements + $this->coveredConditionals + $this->coveredMethods) / ($this->elements + $this->statements + $this->conditionals + $this->methods)) * 100;
    }
}