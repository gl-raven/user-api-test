<?php


namespace Core;


class Router
{
    private static ?Router $instance = null;
    private string $model = '';
    private string $action = '';
    private array $params = [];

    private $routes = [
        'templates' => [
            ['template' => '/^(?<model>\w+)[\/]?(?<id>[\d]+)[\/]?(?<action>\w*)$/u', 'defaultAction' => ''],
            ['template' => '/^(?<model>\w+)[\/]?(?<action>\w*)$/u', 'defaultAction' => ''],
        ],
        'default' => [
            'model' => 'user',
            'action' => 'index',
        ]
    ];

    /**
     * @return Router
     */
    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new Router();
        }

        return self::$instance;
    }

    public function __construct(?string $url = null)
    {
        if (is_null($url)) {
            $parts = parse_url($_SERVER['REQUEST_URI'] ?? '/');
            $url = $parts['path'];
        }

        $this->params = $this->parsePath(trim($url, '/'));
    }

    public function getModel(): string
    {
        if (array_key_exists('model', $this->params)) {
            return $this->params['model'];
        }

        // No match
        throw new \Exception("Model not found");
    }

    public function getAction(): string
    {
        if (array_key_exists('action', $this->params)) {
            return $this->params['action'];
        }

        // No match
        throw new \Exception("Action not found");
    }

    /**
     * @return string
     */
    public function getParam($index): string|false
    {
        return $this->params[$index] ?? false;
    }

    private function parsePath(string $path): array
    {
        if (empty($path)) {
            return [
                'model' => $this->routes['default']['model'],
                'action' => $this->routes['default']['action'],
                'route' => 'default',
            ];
        }

        foreach ($this->routes['templates'] as $route) {
            if (preg_match($route['template'], $path, $matches)) {
                $matches['route'] = $route;
                if (empty($matches['action'])) {
                    $matches['action'] = $route['defaultAction'];
                }

                return $matches;
            }
        }

        // No match
        throw new \Exception("Route not found");
    }
}
