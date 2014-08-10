<?php

namespace Pyrech\CronBundle\Validator\Constraints;

use Pyrech\CronBundle\Util\Operator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class TimePartValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        // Null value should be considered as the asterix operator
        if (null === $value) {
            return;
        }

        $values = array($value);

        if (false !== strpos($value, Operator::SEPARATOR)) {
            list($value, $step) = explode(Operator::SEPARATOR, $value, 2);

            if (Operator::ASTERIX === $value) {
                $values = array($value);
            } elseif (false !== strpos($value, Operator::DASH)) {
                $values = explode(Operator::DASH, $value, 2);
            } else {
                $this->context->addViolation(
                    '"{{ value }}" can not be used with a step',
                    array(
                        '{{ value }}' => $value,
                    )
                );

                return;
            }
            if ('' !== $step) {
                $values[] = $step;
            }
        } elseif (false !== strpos($value, Operator::DASH)) {
            $values = explode(Operator::DASH, $value, 2);
        } elseif (false !== strpos($value, Operator::COMMA)) {
            $values = explode(Operator::COMMA, $value);
        }

        foreach ($values as $value) {
            if (Operator::ASTERIX === $value) continue;
            if (!is_numeric($value)) {
                $this->context->addViolation(
                    '"{{ value }}" is not an integer, nor a valid operator',
                    array(
                        '{{ value }}' => $value,
                    )
                );

                return;
            }
            if ($value < $constraint->minimum || $value > $constraint->maximum) {
                $this->context->addViolation(
                    '{{ value }} is not in the range [{{ minimum }}, {{ maximum }}]',
                    array(
                        '{{ value }}' => $value,
                        '{{ minimum }}' => $constraint->minimum,
                        '{{ maximum }}' => $constraint->maximum,
                    )
                );

                return;
            }
        }
    }
}
