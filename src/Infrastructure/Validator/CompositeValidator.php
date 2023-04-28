<?php

declare(strict_types=1);

namespace Fau\DegreeProgram\Common\Infrastructure\Validator;

use Fau\DegreeProgram\Common\Domain\DegreeProgramDataValidator;
use Fau\DegreeProgram\Common\Domain\Violations;

final class CompositeValidator implements DegreeProgramDataValidator
{
    /** @var array<DegreeProgramDataValidator> */
    private array $validators;

    public function __construct(DegreeProgramDataValidator ...$validators)
    {
        $this->validators = $validators;
    }

    public function validatePublish(array $data): Violations
    {
        $violations = Violations::new();
        foreach ($this->validators as $validator) {
            $violations->add(...$validator->validatePublish($data));
        }

        return $violations;
    }

    public function validateDraft(array $data): Violations
    {
        $violations = Violations::new();
        foreach ($this->validators as $validator) {
            $violations->add(...$validator->validateDraft($data));
        }

        return $violations;
    }
}
