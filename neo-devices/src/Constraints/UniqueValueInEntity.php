<?php declare(strict_types=1);

namespace App\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class UniqueValueInEntity extends Constraint
{
    public ?string $message = 'Это значение уже используется';
    public ?string $class;
    public array   $fields;
    public ?string $identifier;


    public function __construct(string $message = 'Это значение уже используется', string $class = null, array $fields = [], string $identifier = null)
    {
        $this->message    = $message;
        $this->class      = $class;
        $this->fields     = $fields;
        $this->identifier = $identifier;
    }

    public function getRequiredOptions()
    {
        return ['entityClass', 'field'];
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
