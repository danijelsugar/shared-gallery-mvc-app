<?php

namespace Gallery\Core;

use Gallery\Core\Request;
use Gallery\Core\Response;

class Router
{
    private Request $request;
    private Response $response;
    private array $routeMap = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = new $request;
        $this->response = new $response;
    }

    public function get(string $url, $callback): Router
    {
        $this->routeMap['get'][$url] = $callback;
        return $this;
    }

    public function post(string $url, $callback): Router
    {
        $this->routeMap['post'][$url] = $callback;
        return $this;
    }

    public function resolve()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;

        if (!$callback) {
            return 'Not Found';
        }

        if (is_string($callback)) {

        }

        if (is_array($callback)) {
            $controller = new $callback[0];
            $controller->action = $callback[1];

            $callback[0] = $controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }
}