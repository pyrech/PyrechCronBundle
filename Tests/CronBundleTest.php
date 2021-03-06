<?php

namespace Pyrech\CronBundle\Tests;

use Pyrech\CronBundle\Model\Task;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorInterface as LegacyValidatorInterface;

class CronBundleTest extends CronBundleTestCase
{
    /** @var ValidatorInterface|LegacyValidatorInterface */
    private $validator;

    protected function setUp()
    {
        $kernel = self::createKernel();
        $kernel->boot();
        $this->validator = $kernel->getContainer()->get('validator');
    }

    public function testKernelBoots()
    {
        $kernel = self::createKernel();
        $kernel->boot();
    }

    public function testValidationConfigWithValidTask()
    {
        $task = new Task('/usr/bin/php -v');
        $task->setMinute(30);
        $task->setHour(12);
        $task->setDayOfWeek(4);
        $task->setDayOfMonth(15);
        $task->setMonth(6);

        $violationList = $this->validator->validate($task);
        $this->assertEquals(0, $violationList->count());
    }

    public function testValidationConfigWithInvalidTask()
    {
        $task = new Task('/usr/bin/php -v');
        $task->setJob('');

        $violationList = $this->validator->validate($task);
        $this->assertEquals(1, $violationList->count());

        $task = new Task('/usr/bin/php -v');
        $task->setMinute(-1);
        $task->setHour(-1);
        $task->setDayOfWeek(-1);
        $task->setDayOfMonth(-1);
        $task->setMonth(0);

        $violationList = $this->validator->validate($task);
        $this->assertEquals(5, $violationList->count());

        $task = new Task('/usr/bin/php -v');
        $task->setMinute(60);
        $task->setHour(24);
        $task->setDayOfWeek(7);
        $task->setDayOfMonth(32);
        $task->setMonth(13);

        $violationList = $this->validator->validate($task);
        $this->assertEquals(5, $violationList->count());
    }
}
