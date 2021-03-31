<?php

namespace Javanile\Define;

use Webmozart\Glob\Glob;
use Webmozart\PathUtil\Path;
use GuzzleHttp\Client;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use ZipArchive;

use Genesis\Lime\ParseError;

class DefineCommand extends Command
{
    /**
     * Main parser.
     */
    protected $parser;

    /**
     * Debug flag.
     */
    protected $debug;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('define')
            ->setDescription('Build a bundle from a Propan.json file')
            ->addArgument('concept', InputArgument::REQUIRED)
            ->addOption('prefix', null, InputOption::VALUE_REQUIRED, 'Install on specific path', getcwd())
            ->addOption('graph', 'g', InputOption::VALUE_REQUIRED, 'Store Definitions Graph to FILE')
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Run debugger')
        ;
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $concept = $input->getArgument('concept');
        $prefix = $input->getOption('prefix');
        $this->debug = boolval($input->getOption('debug'));

        $this->parser = new ParserEngine();

        $files = Glob::glob(Path::makeAbsolute('**/*.def', Path::makeAbsolute($prefix, getcwd())));
        foreach ($files as $file) {
            if ($this->debug) {
                echo "#[OPEN {$file}]\n";
            }
            $this->parseFile($file);
        }

        if (!$this->parser->isConceptDefined($concept)) {
            $output->writeln("<error>{$concept}</error> is not defined");
            return 1;
        }

        $this->processNotDefinedConcepts();
        $this->processNotRelatedConcepts($concept);
        $this->processInstructionsScope();

        $output->writeln("<info>{$concept}</info> is well-defined");

        //var_dump($this->parser->getGraph());
        //var_dump($this->parser->getStructure());

        return 0;
    }

    /**
     * @param $file
     */
    protected function parseFile($file)
    {
        $line = 1;
        $code = file_get_contents($file);
        try {
            $this->parser->parse($code, $file, $line);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            echo "{$error} on {$file}:{$line}\n";
            exit(2);
        }
    }

    /**
     *
     */
    protected function processNotDefinedConcepts()
    {
        $countNotDefinedConcepts = count($notDefinedConcepts = $this->parser->getNotDefinedConcepts());
        if ($countNotDefinedConcepts > 0) {
            foreach ($notDefinedConcepts as $concept) {
                echo "ERROR: Undefined concept '${concept}'.\n";
            }
            exit(1);
        }
    }

    /**
     * @param $mainConcept
     */
    protected function processNotRelatedConcepts($mainConcept)
    {
        //$countNotRelatedConcepts = count($notRelatedConcepts = $this->parser->parser->getNotRelatedConcepts());
        $notRelatedConcepts = 0;
        $countNotRelatedConcepts = 0;
        if ($countNotRelatedConcepts > 1) {
            foreach ($notRelatedConcepts as $concept) {
                if ($concept == $mainConcept) {
                    continue;
                }
                echo "ERROR: Defined unused concept '${concept}'\n";
            }
            exit(1);
        } elseif ($countNotRelatedConcepts == 1 && $notRelatedConcepts[0] != $mainConcept) {
            echo "ERROR: Main concept '{$mainConcept}' not match with expected '{$notRelatedConcepts[0]}'.\n";
            exit(1);
        }
    }

    /**
     *
     */
    protected function processInstructionsScope()
    {
        $concepts = $this->parser->parser->getDefinedConcepts();

        foreach ($concepts as $concept) {
            //$instructions = $this->parser->parser->getConceptInstructions($concept);
            $instructions = [];
            foreach ($instructions as $instruction) {
                foreach ($instruction as $requiredConcept) {
                    if (!$this->parser->parser->isDefinedConcept($requiredConcept)) {
                        echo "ERROR: Undefined concept '{$requiredConcept}' in instruction.\n";
                        exit(1);
                    } elseif (!$this->parser->parser->discoverConcept($requiredConcept, $concept)) {
                        echo "ERROR: Can't access to '{$requiredConcept}' from '{$concept}'.\n";
                        exit(1);
                    }
                }
            }
        }
    }
}
