<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Validator\Constraints\Password;

use StfalconStudio\ApiBundle\Exception\Validator\UnexpectedConstraintException;
use StfalconStudio\ApiBundle\Util\PasswordRequirementsValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * PasswordMeetSpecialRequirementsValidator.
 */
class PasswordMeetSpecialRequirementsValidator extends ConstraintValidator
{
    /**
     * @param PasswordRequirementsValidator $passwordRequirementsValidator
     */
    public function __construct(private readonly PasswordRequirementsValidator $passwordRequirementsValidator)
    {
    }

    /**
     * @param string|null                                $password
     * @param Constraint|PasswordMeetSpecialRequirements $constraint
     *
     * @throws UnexpectedConstraintException
     */
    public function validate(mixed $password, Constraint|PasswordMeetSpecialRequirements $constraint): void
    {
        if (!$constraint instanceof PasswordMeetSpecialRequirements) {
            throw new UnexpectedConstraintException($constraint, PasswordMeetSpecialRequirements::class);
        }

        if (!empty($password) && !$this->passwordRequirementsValidator->isValid($password)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(PasswordMeetSpecialRequirements::PASSWORD_DOES_NOT_MEET_SPECIAL_REQUIREMENTS)
                ->addViolation()
            ;
        }
    }
}
