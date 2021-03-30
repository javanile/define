<?php

namespace Javanile\Define;

use Symfony\Component\Console\Application as ConsoleApplication;

class Application extends ConsoleApplication
{
    /**
     * Application constructor.
     *
     * @param $context
     */
    public function __construct()
    {
        parent::__construct('Define (MicroDSL)', '0.1.0');
    }
}
