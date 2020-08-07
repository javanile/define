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
            ->addOption('prefix', null, InputOption::VALUE_REQUIRED, 'Install on specific path', getcwd());
        ;
    }

    /**
     * Execute the command.
     *
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Brick successful added.</comment>');

        $prefix = $input->getOption('prefix');

        $this->tokenizer = new Tokenizer();
        $this->parser = new ParseEngine(new DefineParser());

        $files = Glob::glob(Path::makeAbsolute('**/*.def', Path::makeAbsolute($prefix, getcwd())));
        foreach ($files as $file) {
            $this->parseFile($file);
        }

        return 0;
    }

    protected function parseFile($file)
    {
        try {
            $this->parser->reset();
            $stream = $this->tokenizer->tokenize(file_get_contents($file));
            foreach ($stream->tokens as $token) {
                if ($token->type == 'COMMENT' || $token->type == 'WHITESPACE') {
                    continue;
                }
                $this->parser->eat($token->type, $token->value);
            }
            $result = $this->parser->eat_eof();
            var_dump($result);
        } catch ( ParseError $e ) {
            $error = $e->getMessage();
            echo $error."\n";
            exit(2);
        }
    }
}
