<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Model\Factory\ModelFactory;

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
    private $session = [];

    /**
     * @var array
     */
    private $cookie = [];

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
            $this->session = filter_var_array($_SESSION) ?? [];
            $this->cookie   = filter_input_array(INPUT_COOKIE) ?? [];
            $this->get     = filter_input_array(INPUT_GET) ?? [];
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

    /* *************** GETTERS *************** */

    /**
     * Gets SESSION Array
     * @param null|string $var
     * @return array|string
     */
    protected function getSession()
    {
        return $this->session;
    }
    

    /**
     * Gets POST Array or Post Var
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
     * Gets GET Array or Get Var
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
     * Gets USER
     */
    /*protected function getUser()
    {
        if (isset($session) && isset($session['user'])) {
            $session = $this->getSession();
            return $session['user'];
        }
    }*/


    /**
     * Gets USER ID
     * Returns the id of the current logged User
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getUserId()
    {
        $session = $this->getSession();
        $id = $session["user"]["id"];
        return $id;
    }




    /**
     * Returns current ID
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getId()
    {
        return $this->get["id"];
        /*return filter_input(INPUT_GET, "id"); si utilisé qu'une fois*/
    }


    /* ******************** SETTERS ******************** *\

    /**
     * Set Cookie
     * @param string $name
     * @param string $value
     * @param int $expire
     */
    protected function setCookie(string $name, string $value = "", int $expire = 0) {

        if ($expire === 0) {
            $expire = time() + 3600;
        }

        setcookie($name, $value, $expire, "/");
    }


    /* ***************** CHECK ADMIN ***************** */
    /**
     * Checks if logged User is Admin
     * @return bool
     */
    protected function isAdmin() {
        $session = $this->getSession();
        $user = $session['user'];
        
        if (isset($user) && !empty($user) && $user['role'] === "1") {
            return true;
        } else {
            return false;
        }
    }

};