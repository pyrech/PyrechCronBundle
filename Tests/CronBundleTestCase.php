<?php

namespace Pyrech\CronBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CronBundleTestCase extends WebTestCase
{
    protected static function getKernelClass()
    {
        require_once __DIR__.'/Fixtures/app/AppKernel.php';

        return 'Pyrech\CronBundle\Tests\Fixtures\app\AppKernel';
    }
}
