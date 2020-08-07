<?php

namespace JavanileDefine;

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
use Genesis\Lime\ParseEngine;
use Genesis\Lime\ParseError;

class DefaultCommand extends Command
{
    /**
     *
     */
    protected $tokenizer;

    /**
     *
     */
    protected $parser;

    /**
     *
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
            ->addOption('debug', 'd', InputOption::VALUE_NONE, 'Run debugger')
        ;
    }

    /**
     * Execute the command.
     *
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Brick successful added.</comment>');

        $concept = $input->getArgument('concept');
        $prefix = $input->getOption('prefix');
        $this->debug = boolval($input->getOption('debug'));

        $this->tokenizer = new Tokenizer();
        $this->parser = new ParseEngine(new DefineParser());

        $files = Glob::glob(Path::makeAbsolute('**/*.def', Path::makeAbsolute($prefix, getcwd())));
        foreach ($files as $file) {
            if ($this->debug) {
                echo "#[OPEN {$file}]\n";
            }
            $this->parseFile($file);
        }

        #$this->processNotRelatedConcepts($concept);
        $this->processNotDefinedConcepts($concept);

        return 0;
    }

    /**
     * @param $file
     */
    protected function parseFile($file)
    {
        try {
            $this->parser->reset();
            $stream = $this->tokenizer->tokenize(file_get_contents($file));
            foreach ($stream->tokens as $token) {
                if ($this->debug) {
                    echo "#[{$token->type} {$file}:] ".json_encode($token->value)."\n";
                }
                if ($token->type == 'COMMENT' || $token->type == 'WHITESPACE') {
                    continue;
                }
                $this->parser->eat($token->type, $token->value);
            }
            $result = $this->parser->eat_eof();
            //var_dump($result);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            echo $error."\n";
            exit(2);
        }
    }

    /**
     *
     */
    protected function processNotRelatedConcepts()
    {
        $countNotRelatedConcepts = count($notRelatedConcepts = $this->parser->parser->getNotRelatedConcepts());
        if ($countNotRelatedConcepts > 1) {
            foreach ($notRelatedConcepts as $concept) {
                echo "ERROR: Undefined concept '${concept}' at\n";
            }
            exit(1);
        } elseif ($countNotRelatedConcepts == 1 && $notRelatedConcepts[0] != $concept) {
            echo "ERROR: Main concept '${concept}' not match with expected '{$notRelatedConcepts[0]}.'\n";
            exit(1);
        }
    }

    /**
     *
     */
    protected function processNotDefinedConcepts()
    {
        $countNotDefinedConcepts = count($notDefinedConcepts = $this->parser->parser->getNotDefinedConcepts());
        if ($countNotDefinedConcepts > 0) {
            foreach ($notDefinedConcepts as $concept) {
                echo "ERROR: Undefined concept '${concept}' at\n";
            }
            exit(1);
        } elseif ($countNotRelatedConcepts == 1 && $notRelatedConcepts[0] != $concept) {
            echo "ERROR: Main concept '${concept}' not match with expected '{$notRelatedConcepts[0]}.'\n";
            exit(1);
        }
    }
}
