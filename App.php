<?php


use Api\AbstractApi;
use Core\Request;
use Core\Response;
use Core\Router;
use Api\User;
use Api\Group;
use db\Connection;

final class App
{
    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * @var Router
     */
    private Router $router;

    public static $pathToApp = '';

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        self::$pathToApp = dirname(realpath(__FILE__));
        $this->loadFiles();
        Connection::init($config);
        try {
            $this->request = Request::getInstance();
            $this->response = Response::getInstance();
            $this->router = Router::getInstance();
        } catch (\Exception $e) {
            $this->response->setCode(500);
            $this->response->assign(['error' => $e->getMessage()]);
            $this->response->printResponse();
            exit;
        }
        
    }

    private function loadFiles(): void
    {
        require_once self::$pathToApp . "/db/Connection.php";
        require_once self::$pathToApp . "/Mapper/AbstractMapper.php";
        require_once self::$pathToApp . "/Mapper/UserMapper.php";
        require_once self::$pathToApp . "/db/Connection.php";
        require_once self::$pathToApp . "/Core/Request.php";
        require_once self::$pathToApp . "/Core/Router.php";
        require_once self::$pathToApp . "/Core/Response.php";
        require_once self::$pathToApp . "/Api/AbstractApi.php";
        require_once self::$pathToApp . "/Api/User.php";
        require_once self::$pathToApp . "/Core/Validator.php";
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $api = $this->getApiObject($this->router->getModel());

        if (!is_null($api)){
            $api->run();
        }

        $this->response->printResponse();
    }

    /**
     * @param $name
     * @return AbstractApi|null
     */
    public function getApiObject($name): ?AbstractApi
    {
        $model = ucfirst($name);

        if (empty($model)) {
            return null;
        }

        $class = 'Api\\' . $model;
        if (!class_exists($class)) {
            return null;
        }

        $api = new $class();

        if (!($api instanceof AbstractApi)) {
            return null;
        }

        return $api;
    }
}
