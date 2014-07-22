<?php

namespace Pyrech\CronBundle\Tests;

class CronBundleTest extends CronBundleTestCase
{
    public function testKernelBoots()
    {
        $kernel = self::createKernel();
        $kernel->boot();
    }
}
