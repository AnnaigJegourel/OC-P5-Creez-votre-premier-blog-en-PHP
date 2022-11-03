<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\View\TwigExtension;
use App\Model\Factory\ModelFactory;

/**
 * Class MainController
 * 
 * Manages the Main Features
 * 
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
    private $get = [];

    /**
     * MainController constructor
     * 
     * Creates the Template Engine & adds its Extensions
     */
    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader("../src/View"), array("cache"=>false));
        $this->twig->addExtension(new TwigExtension());
        $this->session = filter_var_array($_SESSION) ?? [];
        $this->get     = filter_input_array(INPUT_GET) ?? [];
    }
    
    /**
     * Redirects to another URL
     * 
     * @param string $page
     * @param array $params
     */
    protected function redirect(string $page, array $params = [])
    {
        $params["access"] = $page;
        header("Location: index.php?" . http_build_query($params));
        
        exit;
    }

    /**
     * Returns string value with slashes where necessary
     * 
     * @param string $input
     * 
     * @return string
     */
    protected function putSlashes(string $input)
    {
        return addslashes($input);
    }

    /* *************** SETTER *************** */
    /**
     * Sets MESSAGE
     * 
     * @param string $message
     */
    public function setMessage($message)
    {
        $_SESSION["message"] = $message;
    }

    /* *************** GETTERS *************** */
    /**
     * Gets POST Array or Post Var
     * 
     * @param null|string $var
     * 
     * @return array|string
     */
    protected function getPost(string $var = null)
    {
        if ($var === null) {

            return filter_input_array(INPUT_POST);
        }

        return filter_input(INPUT_POST, $var);
    }

    /**
     * Gets GET Array or Get Var
     * 
     * @param string|null $var
     * 
     * @return array|string
     */
    protected function getGet(string $var = null)
    {
        if ($var === null) {

            return filter_input_array(INPUT_GET);
        }

        return filter_input(INPUT_GET, $var);
    }

    /**
     * Gets SESSION Array
     * 
     * @param null|string $var
     * 
     * @return array|string
     */
    protected function getSession()
    {
        return $this->session;
    }

    /**
     * Returns current ID
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getId()
    {
        return $this->get["id"];
    }

    /**
     * Gets USER
     * 
     * Returns the data of User with id or of logged User
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getUser($id = null)
    {
        if (isset($id) && !empty($id)) {
            $user = ModelFactory::getModel("User")->readData((string)$id);
        } else {
            $session = $this->getSession();
            $user = $session["user"];
        }

        return $user;
    }

    /**
     * Gets USER ID
     * 
     * Returns the id of the current logged User
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    protected function getUserId()
    {
        $session = $this->getSession();
        if(isset($session["user"]) && !empty($session["user"])){
            $id = $session["user"]["id"];

            return $id;    
        }
    }

    /* ***************** BOOL / CHECKERS ***************** */
    /**
     * Checks if a user is connected
     * 
     * @return bool
     */
    public function isLogged()
    {
        $session = $this->getSession();
        if(!empty($session) && isset($session["user"]) && !empty($session["user"])) {

            return true;
        }
    }

    /**
     * Checks if logged User is Admin
     * 
     * @return bool
     */
    public function isAdmin()
    {
        if ($this->isLogged() && $this->getUser()["role"] === "1"){

            return true;
        }
    }
}