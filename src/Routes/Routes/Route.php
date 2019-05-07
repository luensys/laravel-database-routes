<?php

namespace Douma\Routes\Routes;

class Route
{
    static $NULL;
    private $url, $isPattern=false, $name, $controller, $action, $parameters, $getParameters;

    public function __construct(string $url, bool $isPattern, string $name,
        string $controller, string $action
    ) {
        $this->url = $url;
        $this->isPattern = $isPattern;
        $this->name = $name;
        $this->controller = $controller;
        $this->action = $action;
    }

    private function replaceParameters(string $url)
    {
        if(strpos($url, '{') > -1 && !empty($this->parameters)) {
            foreach($this->parameters as $key=>$parameter) {
                $url = str_replace('{'.$key.'}', $parameter, $url);
            }
        }
        return $url;
    }

    public function url()
    {
        $getParameters = "";
        if(!empty($this->getParameters)) {
            $getParameters = "?" . http_build_query($this->getParameters);
        }
        return $this->replaceParameters($this->url) . $getParameters;
    }

    public function name()
    {
        return $this->name;
    }

    public function isPattern()
    {
        return $this->isPattern;
    }

    public function controller()
    {
        return $this->controller;
    }

    public function action()
    {
        return $this->action;
    }

    public function parameters()
    {
        $this->parameters;
    }

    public function withUrl(string $url)
    {
        $route = clone $this;
        $route->url = $url;
        return $route;
    }
    
    public function withGetParameters(array $parameters)
    {
        $route = clone $this;
        $route->getParameters = $parameters;
        return $route;
    }

    public function withName(string $name)
    {
        $route = clone $this;
        $route->name = $name;
        return $route;
    }

    public function withIsPattern(boolean $isPattern)
    {
        $route = clone $this;
        $route->isPattern = $isPattern;
        return $route;
    }

    public function withController(string $controller)
    {
        $route = clone $this;
        $route->controller = $controller;
        return $route;
    }

    public function withAction(string $action)
    {
        $route = clone $this;
        $route->action = $action;
        return $route;
    }

    public function withParameters(array $parameters)
    {
        $route = clone $this;
        $route->parameters = $parameters;
        return $route;
    }

    public function withParameter(string $parameter, $value)
    {
        $route = clone $this;
        $route->parameters[$parameter] = $value;
        return $route;
    }
}
