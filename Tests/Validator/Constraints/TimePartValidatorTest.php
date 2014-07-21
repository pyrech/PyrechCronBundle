<?php

namespace Pyrech\CronBundle\Tests\Validator\Constraints;

use Pyrech\CronBundle\Validator\Constraints\TimePart;
use Pyrech\CronBundle\Validator\Constraints\TimePartValidator;

class TimePartValidatorTest extends \PHPUnit_Framework_TestCase
{
    protected $context;
    protected $validator;

    protected function setUp()
    {
        $this->context = $this->getMock('Symfony\Component\Validator\ExecutionContext', array(), array(), '', false);
        $this->validator = new TimePartValidator();
        $this->validator->initialize($this->context);
    }

    /**
     * @dataProvider getValidTimeParts
     */
    public function testValidTimePart($timePart)
    {
        $constraint = new TimePart(array(
            'minimum' => 1,
            'maximum' => 12,
        ));

        $this->context->expects($this->never())->method('addViolation');

        $this->validator->validate($timePart, $constraint);
    }

    /**
     * @dataProvider getInvalidRangesWithStep
     */
    public function testInvalidRangeWithStep($timePart, $range)
    {
        $constraint = new TimePart(array(
            'minimum' => 1,
            'maximum' => 12,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('"{{ value }}" can not be used with a step', array(
                '{{ value }}' => $range,
            ));

        $this->validator->validate($timePart, $constraint);
    }

    /**
     * @dataProvider getInvalidWithUnknownTokens
     */
    public function testInvalidWithUnknownToken($timePart, $unknownToken)
    {
        $constraint = new TimePart(array(
            'minimum' => 1,
            'maximum' => 12,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('"{{ value }}" is not an integer, nor a valid operator', array(
                '{{ value }}' => $unknownToken,
            ));

        $this->validator->validate($timePart, $constraint);
    }

    /**
     * @dataProvider getInvalidWithValuesNotInRange
     */
    public function testInvalidWithValueNotInRange($timePart, $value)
    {
        $constraint = new TimePart(array(
            'minimum' => 1,
            'maximum' => 12,
        ));

        $this->context->expects($this->once())
            ->method('addViolation')
            ->with('{{ value }} is not in the range [{{ minimum }}, {{ maximum }}]', array(
                '{{ value }}' => $value,
                '{{ minimum }}' => 1,
                '{{ maximum }}' => 12,
            ));

        $this->validator->validate($timePart, $constraint);
    }

    public function getValidTimeParts()
    {
        return array(
            array(null),
            array('*'),

            array('1'),
            array('6'),
            array('12'),

            array('1,2'),
            array('1,2,3,4,12'),
            array('7,12'),
            array('1,12'),

            array('1-2'),
            array('3-5'),
            array('8-12'),
            array('1-12'),

            array('1-12/'),
            array('1-12/2'),
            array('1-5/2'),
            array('6-12/1'),
            array('1-12/6'),
            array('1-12/12'),

            array('*/'),
            array('*/1'),
            array('*/6'),
            array('*/12'),
        );
    }

    public function getInvalidRangesWithStep()
    {
        return array(
            array(' /', ' '),
            array(' /2', ' '),

            array('1/', '1'),
            array('1/2', '1'),

            array('a/', 'a'),
            array('a/2', 'a'),

            array('1,5/', '1,5'),
            array('1,5/2', '1,5'),
            array('1,5,10/', '1,5,10'),
            array('1,5,10/2', '1,5,10'),
        );
    }

    public function getInvalidWithUnknownTokens()
    {
        return array(
            array('', ''),
            array(' ', ' '),
            array('a', 'a'),

            array(',', ''),
            array('1,', ''),
            array(',5', ''),
            array('1,5,8,a', 'a'),
            array('1,5,8, ', ' '),

            array('-', ''),
            array('1-', ''),
            array('1- ', ' '),
            array('-5', ''),
            array('1-a', 'a'),
            array('a-6', 'a'),
            array(' -6', ' '),

            array('1-12/a', 'a'),
            array('1-12/ ', ' '),
            array('1-b/2', 'b'),
            array('6- /1', ' '),

            array('*/ ', ' '),
            array('*/a', 'a'),
            array('*/1-6', '1-6'),
        );
    }

    public function getInvalidWithValuesNotInRange()
    {
        return array(
            array('0', '0'),
            array('13', '13'),

            array('0,6', '0'),
            array('1,2,3,4,13', '13'),
            array('7,13', '13'),

            array('0-2', '0'),
            array('3-13', '13'),

            array('0-12/', '0'),
            array('1-13/6', '13'),
            array('1-12/13', '13'),

            array('*/0', '0'),
            array('*/13', '13'),
        );
    }
}
