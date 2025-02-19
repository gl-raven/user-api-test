<?php


namespace Core;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private static ?Request $instance = null;
    private array $data = [];
    private string $method = Request::GET;

    /**
     * @return Request
     */
    public static function getInstance(): Request
    {
        if (is_null(self::$instance)) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    public function __construct()
    {
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->setMethod($_SERVER['REQUEST_METHOD']);
        }

        if ($this->getMethod() !== Request::GET) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data)) {
                $data = [];
            }
            $this->setData($data);
        }
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(?string $method = null): void
    {
        if (!in_array($method, [self::GET, self::POST, self::PUT, self::DELETE])) {
            return;
        }
        $this->method = $method;
    }

    public function get($key, $method, $default = null): ?string
    {
        if ($method === self::GET) {
            return $_GET[$key] ?? $default;
        }

        return $this->data[$method][$key] ?? $default;
    }

    public function setData(array $data): void
    {
        if ($this->method === self::GET) {
            $_GET = $data;
            return;
        }

        $this->data[$this->method] = $data;
    }
}
