<?php

namespace Core\Validator;

class RequiredFieldValidator extends AbstractFieldValidator
{
    public function __construct(string $field, string $message) 
    {
        parent::__construct($field, $message);
    }
    public function validate($value): bool
    {
        return !empty($value);
    }
}