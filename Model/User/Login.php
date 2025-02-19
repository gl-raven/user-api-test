<?php

namespace Model\User;

use App;
use Core\Request;
use Core\Validator;
use db\Connection;
use Mapper\UserMapper;
use Model\IValidate;

require_once App::$pathToApp . "/Model/IValidate.php";


class Login implements IValidate
{
    private $data;

    private Validator$validator;

    private UserMapper $userMapper;

    private Request $request;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->userMapper = new UserMapper(Connection::getInstance());
        $this->request = Request::getInstance();
        $this->validator = new Validator();
    }

    public function load($data)
    {
        $this->data = $data;
    }

    public function validate()
    {
        $this->validator->add('required', 'login', 'Login is required.')
            ->add('required', 'password', 'Password is required.')
            ->add('callback', 'login', 'Invalid login or password.', [
                'callback' => function ($value) {
                    $user = $this->userMapper->findByLogin($value);
                    if (empty($user)) {
                        return false;
                    }

                    $password = $this->request->get('password', Request::POST, '');
                    return password_verify($password, $user['password']);
                }
            ]);

        $this->validator->load($this->data);
        return $this->validator->validate();
    }

    public function getErrors(): array
    {
        return $this->validator->getErrors();
    }

    public function login(): array
    {
        return $this->userMapper->findByLogin($this->data['login']);
    }
}