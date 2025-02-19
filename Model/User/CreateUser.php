<?php

namespace Model\User;

use App;
use Core\Validator;
use db\Connection;
use Model\IValidate;
use Mapper\UserMapper;

require_once App::$pathToApp . "/Model/IValidate.php";

class CreateUser implements IValidate
{
    private $data;
    private $validator;
    private $userMapper;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->userMapper = new UserMapper(Connection::getInstance());
    }

    public function load($data)
    {
        $this->data = $data;
    }

    public function validate()
    {
        $this->validator->add('required', 'login', 'Login is required.')
            ->add('required', 'password', 'Password is required.')
            ->add('required', 'email', 'Email is required.')
            ->add(
                'callback',
                'login',
                'Login already exists.',
                [
                    'callback' => function ($value) {
                        return !$this->userMapper->findByLogin($value);
                    }
                ]
            )
            ->add(
                'callback',
                'email',
                'Invalid email.',
                [
                    'callback' => function ($value) {
                        return filter_var($value, FILTER_VALIDATE_EMAIL);
                    }
                ]
            );

        $this->validator->load($this->data);
        return $this->validator->validate();
    }

    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }

    public function save(): int
    {
        return $this->userMapper->createUser($this->data['login'], $this->data['password'], $this->data['email']);
    }
}