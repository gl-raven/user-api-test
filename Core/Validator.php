<?php

namespace Core;

use \App;
class Validator {

    private $errors = [];
    private $hasErrors = false;

    private $validators = [];

    private $data = [];

    public function __construct() {
        require_once App::$pathToApp . "/Core/Validator/AbstractFieldValidator.php";
        require_once App::$pathToApp . "/Core/Validator/RequiredFieldValidator.php";
        require_once App::$pathToApp . "/Core/Validator/CallbackFieldValidator.php";
    }

    public function add($rule, $field, $message, $params = []): self 
    {
        if (!isset($this->validators[$field])) {
            $this->validators[$field] = [];
        }

        /**
         * @see \Core\Validator\AbstractFieldValidator
         */
        $class = "Core\Validator\\" . ucfirst($rule) . "FieldValidator";

        if (!class_exists($class)) {
            throw new \Exception("Validator class {$class} not found");
        }

        $this->validators[$field][] = new $class($field, $message, $params);

        return $this;
    }

    public function load(array $data): void
    {
        $this->data = $data;
    }

    public function validate(): bool
    {
        foreach ($this->validators as $field => $validators) {
            $fieldValue = $this->data[$field] ?? '';

            foreach ($validators as $validator) {
                if (!$validator->validate($fieldValue)) {
                    $this->errors[$field] = $validator->getMessage();
                    $this->hasErrors = true;
                    break;
                }
            }
        }

        return !$this->hasErrors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setError($field, $message) {
        $this->errors[$field] = $message;
    }
}