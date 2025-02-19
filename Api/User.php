<?php

namespace Api;

use App;
use Core\Request;
use Core\Router;
use Core\Validator;
use Mapper\ActionMapper;
use Mapper\UserMapper;
use Model\User\CreateUser;
use Model\User\Login;
use Model\User\UpdateUser;
use PDO;

class User extends AbstractApi
{
    private UserMapper $userMapper;
    private ActionMapper $actionMapper;
    protected Router $router;

    public function __construct()
    {
        parent::__construct();

        $this->loadFiles();

        $this->userMapper = new UserMapper($this->db);
        $this->router = Router::getInstance();
    }

    public function loadFiles(): void
    {
        require_once App::$pathToApp . "/Model/User/CreateUser.php";
        require_once App::$pathToApp . "/Model/User/UpdateUser.php";
        require_once App::$pathToApp . "/Model/User/Login.php";
    }

    public function actionCreate(): void
    {
        $data = [
            'login' => $this->request->get('login', Request::POST, ''),
            'password' => $this->request->get('password', Request::POST, ''),
            'email' => $this->request->get('email', Request::POST, ''),
        ];

        $model = new CreateUser();
        $model->load($data);
        if (!$model->validate()) {
            $this->response->setCode(400);
            $this->response->assign(['errors' => $model->getErrors()]);
            return;
        }

        $this->response->assign([
            'success' => '1',
            'data' => [
                'id' => $model->save(),
            ],
        ]);
    }

    public function actionLogin(): void
    {
        $params = [
            'login' => $this->request->get('login', Request::POST, ''),
            'password' => $this->request->get('password', Request::POST, ''),
        ];

        $model = new Login();
        $model->load($params);
        
        if (!$model->validate()) {
            $this->response->setCode(400);
            $this->response->assign(['errors' => $model->getErrors()]);

            return;
        }

        $user = $model->login();
        $this->response->assign([
            'success' => '1',
            'data' => $this->getUserData($user),
        ]);
    }

    public function actionView(): void
    {
        $id = $this->router->getParam('id');
        $user = $this->findUser($id);
        if (empty($user)) {
            $this->response->setCode(404);
            $this->response->assign(['error' => 1]);
            return;
        }

        $this->response->assign([
            'success' => '1',
            'data' => $this->getUserData($user),
        ]);
    }

    public function actionDelete(): void
    {
        $this->response->assign(
            [
                'success' => (int) $this->userMapper->deleteUser($this->router->getParam('id'))
            ]
        );
    }

    public function actionUpdate(): void
    {
        $data = [
            'email' => $this->request->get('email', Request::PUT, ''),
        ];

        $model = new UpdateUser((int) $this->router->getParam('id'));
        $model->load($data);
        if (!$model->validate()) {
            $this->response->setCode(400);
            $this->response->assign(['errors' => $model->getErrors()]);

            return;
        }

        $this->response->assign(
            [
                'success' => (int) $model->save(),
            ]
        );
    }

    private function getUserData(array $user): array
    {
        return [
            'id' => $user['id'],
            'login' => $user['login'],
            'email' => $user['email'],
        ];
    }

    public function findUser($user_id): array|false
    {
        return $this->userMapper->findUser($user_id);
    }

}
