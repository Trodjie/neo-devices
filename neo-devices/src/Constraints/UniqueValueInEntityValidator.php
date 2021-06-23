<?php declare(strict_types=1);

namespace App\Constraints;


use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueValueInEntityValidator extends ConstraintValidator
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var \App\Constraints\UniqueValueInEntity $constraint */

        if (!is_array($constraint->fields)) {
            throw new UnexpectedTypeException($constraint->fields, 'array');
        }

        if (0 === count($constraint->fields)) {
            throw new ConstraintDefinitionException('At least one field has to be specified.');
        }

        $objectManager = $this->registry->getManagerForClass($constraint->class);

        if (!$objectManager) {
            throw new ConstraintDefinitionException(sprintf('Unable to find the object manager associated with an entity of class "%s".', $constraint->class));
        }

        /* @var \Doctrine\ORM\Mapping\ClassMetadata $class  */
        $class = $objectManager->getClassMetadata($constraint->class);
        $criteria = [];

        foreach ($constraint->fields as $fieldName) {
            if (!$class->hasField($fieldName) && !$class->hasAssociation($fieldName)) {
                throw new ConstraintDefinitionException(sprintf('The field "%s" is not mapped by Doctrine, so it cannot be validated for uniqueness.', $fieldName));
            }

            $criteria[$fieldName] = $value->$fieldName;

            if (null !== $criteria[$fieldName] && $class->hasAssociation($fieldName)) {
                /* Ensure the Proxy is initialized before using reflection to
                 * read its identifiers. This is necessary because the wrapped
                 * getter methods in the Proxy are being bypassed.
                 */
                $objectManager->initializeObject($criteria[$fieldName]);
            }
        }

        $repository = $objectManager->getRepository($constraint->class);

        $result = $repository->findBy($criteria);
        if ($result instanceof \IteratorAggregate) {
            $result = $result->getIterator();
        }

        /* If the result is a MongoCursor, it must be advanced to the first
         * element. Rewinding should have no ill effect if $result is another
         * iterator implementation.
         */
        if ($result instanceof \Iterator) {
            $result->rewind();
        } elseif (is_array($result)) {
            reset($result);
        }

        if (0 === count($result)) {
            return;
        }

        $identifierField = $constraint->identifier;
        if ($identifierField && $identifierValue = $value->$identifierField) {
            $classMetadata = $objectManager->getClassMetadata($constraint->class);
            $filterIdentifiers = array_filter(is_array($result) ? $result : iterator_to_array($result), function($value) use ($classMetadata, $identifierValue) {
                $id = current($classMetadata->getIdentifierValues($value));

                return $id !== $identifierValue;
            });

            if (0 === count($filterIdentifiers)) {
                return;
            }

            $result = $filterIdentifiers;
        }

        foreach ($constraint->fields as $modelField => $objectField) {
            $this->context->buildViolation($constraint->message)
                ->setCause($result)
                ->setInvalidValue(implode(', ', $criteria))
                ->atPath($modelField)
                ->addViolation()
            ;
        }
    }
}
