<?php

namespace Pyrech\CronBundle\Tests\Fixtures\app;

use Pyrech\CronBundle\PyrechCronBundle;
use Pyrech\CronBundle\Tests\Fixtures\src\CommandInvalidBundle\CommandInvalidBundle;
use Pyrech\CronBundle\Tests\Fixtures\src\CommandValidBundle\CommandValidBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    private $registerInvalidBundle = false;

    public function registerBundles()
    {
        $bundles = array(
            new FrameworkBundle(),
            new PyrechCronBundle(),
            new CommandValidBundle()
        );

        if ($this->registerInvalidBundle) {
            $bundles[] = new CommandInvalidBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/' . $this->getEnvironment() . '.yml');
    }

    public function activeInvalidBundle()
    {
        $this->registerInvalidBundle = true;
    }
}
