<?php

namespace App\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Model\Factory\ModelFactory;


/**
 * Class TwigExtension
 * 
 * @package App\View
 */
class TwigExtension extends AbstractExtension
{
    /**
     * @var array
     */
    private $session = [];

    /**
     * TwigExtension constructor
     */
    public function __construct()
    {
        $this->session = filter_var_array($_SESSION) ?? [];
    }

    /**
     * @return array|TwigFunction[]
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction("getSession", [$this, "getSession"]),
            new TwigFunction("getMessage", [$this, "getMessage"]),
            new TwigFunction("getUser", [$this, "getUser"]),
            new TwigFunction("isLogged", [$this, "isLogged"]),
            new TwigFunction("isAdmin", [$this, "isAdmin"]),
        );
    }

    /**
     * Gets SESSION Array
     * 
     * @param null|string $var
     * 
     * @return array|string
     */
    private function getSession()
    {
        return $this->session;
    }

    /**
     * Gets MESSAGE
     * 
     * @return string
     */
    public function getMessage()
    {
        $session = $this->getSession();
        if(isset($session["message"]) && !empty($session["message"])){
            $message = $session["message"];
            echo filter_var($message); 
            unset($_SESSION["message"]);
        }
    }

    /**
     * Checks if a user is connected
     * 
     * @return bool
     */
    public function isLogged(){
        $session = $this->getSession();
        if(!empty($session) && isset($session["user"]) && !empty($session["user"])) {
            return true;
        }
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
    private function getUser($id = null)
    {
        if (isset($id) && !empty($id)) {
            $user = ModelFactory::getModel("User")->readData((string) $id);
        } else {
            $session = $this->getSession();
            $user = $session["user"];
        }       
        return $user;
    }

    /**
     * Checks if logged User is Admin
     * 
     * @return bool
     */
    public function isAdmin() {
        if ($this->isLogged() && $this->getUser()["role"] === "1"){
            return true;
        }
    }
}