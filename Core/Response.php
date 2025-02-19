<?php
namespace Core;

class Response
{
    private static ?Response $instance = null;

    private int $code = 200;

    /**
     * @return Response
     */
    public static function getInstance(): Response
    {
        if (is_null(self::$instance)) {
            self::$instance = new Response();
        }

        return self::$instance;
    }

    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    protected array $data = [];

    public function assign($data = []): void
    {
        $this->data = $data;
    }

    public function getJsonData(): string
    {
        return json_encode($this->data);
    }

    public function printResponse(): void
    {
        http_response_code($this->getCode());
        header("Content-type: application/json; charset=utf-8");
        print $this->getJsonData();
    }
}
