<?php

namespace Pyrech\CronBundle\Tests\Fixtures\app;

use Pyrech\CronBundle\PyrechCronBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new FrameworkBundle(),
            new PyrechCronBundle(),
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/' . $this->getEnvironment() . '.yml');
    }
}
