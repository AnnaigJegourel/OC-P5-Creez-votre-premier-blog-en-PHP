<?php

namespace App;

/**
 * Class Router
 * manages routes to show Views
 * @package App
 */
class Router
{
    /**
     * Default path to all controllers
     */
    const DEFAULT_PATH = "App\Controller\\";

    /**
     * Default controller
     */
    const DEFAULT_CONTROLLER = "HomeController";

    /**
     * Default method
     */

    const DEFAULT_METHOD = "defaultMethod";

    /**
     * Requested controller
     * @var string
     */
    private $controller = self::DEFAULT_CONTROLLER;

    /**
     * Requested method
     * @var string
     */
    private $method = self::DEFAULT_METHOD;

    /**
     * Router constructor
     * parses the URL, sets the controller & his method
     */
    public function __construct()
    {
        $this->parseUrl();
        $this->setController();
        $this->setMethod();
    }

    /**
     * Parses the URL to get the controller & his method
     */
    public function parseUrl()                           
    {
        $access = filter_input(INPUT_GET, "access");   

        if (!isset($access)) {
            $access = "home";
        }

        $access             = explode("!", $access);
        $this->controller   = $access[0];
        $this->method       = count($access) == 1 ? "default" : $access[1];
    }

    /**
     * Sets the requested controller
     */
    public function setController()
    {
        $this->controller = ucfirst(strtolower($this->controller)) . "Controller";
        $this->controller = self::DEFAULT_PATH . $this->controller;

        if(!class_exists($this->controller)) {
            $this->controller = self::DEFAULT_PATH . self::DEFAULT_CONTROLLER;
        }
    }

    /**
     * Sets the requested Method
     */
    public function setMethod()
    {
        $this->method = strtolower($this->method) . "Method";

        if (!method_exists($this->controller, $this->method))
        {
            $this->method = self::DEFAULT_METHOD;
        }
    }

    /**
     * Creates the controller object & calls the method on it
     */
    public function run()
    {
        $this->controller   = new $this->controller();
        $response           = call_user_func([$this->controller, $this->method]);

        echo filter_var($response);
    }
}