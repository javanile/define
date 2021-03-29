<?php

namespace Javanile\Define;

class Application extends \Symfony\Component\Console\Application
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
