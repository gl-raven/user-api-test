<?php

namespace Core\Validator;

class CallbackFieldValidator extends AbstractFieldValidator
{
    public function __construct(string $field, string $message, array $params = []) 
    {
        parent::__construct($field, $message, $params);
    }
    public function validate($value): bool
    {
        if (empty($value)) {
            return true;
        }
        if (!isset($this->params['callback'])) {
            throw new \Exception("Callback is not set");
        }

        if (!is_callable($this->params['callback'])) {
            throw new \Exception("Callback is not callable");
        }

        return $this->params['callback']($value);
    }
}