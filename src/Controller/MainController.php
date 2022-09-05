<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class MainController
 * Manages the Main Features
 * @package App\Controller
 */
abstract class MainController
{
    /**
     * @var Environment|null
     */
    protected $twig = null;

    /**
     * @var array
     */
    /*private $session = [];*/

    /**
     * @var array
     */
    /*private $post = [];*/

        /**
     * @var array
     */
    private $get = [];


    /**
     * MainController constructor
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
        {
            $this->twig = new Environment(new FilesystemLoader("../src/View"), array("cache"=>false));
            /*$this->session = filter_var_array($_SESSION) ?? [];*/
            /*$this->post     = filter_input_array(INPUT_POST) ?? [];*/
            $this->get     = filter_input_array(INPUT_GET) ?? [];
        }
    
    /**
     * Get Session Array
     * @param null|string $var
     * @return array|string
     */
    protected function getSession()
    {
        return filter_var_array($_SESSION) ?? [];
    }

    /**
     * Gets Post Array or Post Var
     * @param null|string $var
     * @return array|string
     */
    protected function getPost(string $var = null)
    {
        if ($var === null) {

            /* return $this->post;*/
            return filter_input_array(INPUT_POST);
        }

        /* return $this->post[$var] ?? "";*/
        return filter_input(INPUT_POST, $var);

    }

    /**
     * Gets Get Array or Get Var
     * @param null|string $var
     * @return array|string
     */
    protected function getGet(string $var = null)
    {
        if ($var === null) {

            return $this->get;
            /*return filter_input_array(INPUT_GET);*/
        }

        return $this->get[$var] ?? "";
        /*return filter_input(INPUT_GET, $var);*/
    }

    /**
     * Returns current id
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getId()
    {
        return $this->get['id'];
        /*return filter_input(INPUT_GET, 'id'); si utilisé qu'une fois*/
    }

    /**
     * Redirects to another URL
     * @param string $page
     * @param array $params
     */
    public function redirect(string $page, array $params = [])
    {
        $params["access"] = $page;
        header("Location: index.php?" . http_build_query($params));
        
        exit;
    }
};