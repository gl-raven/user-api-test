<?php


namespace Api;


use Core\Request;
use Core\Response;
use Core\Router;
use db\Connection;
use PDO;

abstract class AbstractApi
{
    protected $action = '';
    /**
     * @var Response
     */
    protected Response $response;

    /**
     * @var Request
     */
    protected Request $request;

    protected PDO $db;

    protected $methodActionRel = [
        Request::GET => 'view',
        Request::POST => 'create',
        Request::PUT => 'update',
        Request::DELETE => 'delete',
    ];

    /**
     * @var Router
     */
    protected Router $router;

    public function __construct()
    {
        $this->response = Response::getInstance();
        $this->request = Request::getInstance();
        $this->router = Router::getInstance();
        $this->db = Connection::getInstance();

        // Запрашиваемое действие или действие согласно методу запроса
        $action = $this->router->getAction()?: $this->findActionByMethod();

        $action = 'action' . ucfirst($action);
        if (!method_exists($this, $action)) {
            // Действие по умолчанию
            throw new \Exception('Action not found');
        }

        $this->action = $action;
    }

    public function actionIndex(): void
    {
        $this->response->assign(['action' => 'index']);
    }

    public function run(): void
    {
        call_user_func(array($this, $this->action));
    }

    private function findActionByMethod()
    {
        if (!isset($this->methodActionRel[$this->request->getMethod()])) {
            throw new \Exception('Method not found');
        }

        return $this->methodActionRel[$this->request->getMethod()];
    }
}
