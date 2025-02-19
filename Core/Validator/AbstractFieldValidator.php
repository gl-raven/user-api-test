<?php

namespace Core\Validator;

abstract class AbstractFieldValidator {
    public function __construct(protected string $field, protected string $message, protected array $params = [] ) 
    {
    }
    public function getMessage(): string
    {
        return $this->message;
    }
    
    abstract public function validate($value): bool;
}