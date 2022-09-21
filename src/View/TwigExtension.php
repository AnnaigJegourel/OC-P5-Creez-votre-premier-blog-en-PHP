<?php

namespace App\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Model\Factory\ModelFactory;


/**
 * Class TwigExtension
 * @package App\View
 */
class TwigExtension extends AbstractExtension
{
     /**
     * @var array
     */
    private $session = [];

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
            new TwigFunction("getUser", [$this, "getUser"]),
            new TwigFunction("isLogged", [$this, "isLogged"]),
            new TwigFunction("isAdmin", [$this, "isAdmin"]),
        );
    }


/**
     * Gets SESSION Array
     * @param null|string $var
     * @return array|string
     */
    public function getSession()
    {
        return $this->session;
    }
    /**
     * Checks if a user is connected
     * @return bool
     */
    public function isLogged(){
        $session = $this->getSession();
        if(!empty($session) && isset($session['user']) && !empty($session['user'])) {
            return true;
        }
    }

    /**
     * Parse a value & return it as string
     *
     * @param mixed $val
     * @return void
     */
    protected function toString(mixed $val) {
        return strval($val);
    }

    /**
     * Gets USER
     * Returns the data of User with id or of logged User
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getUser($id = null)
    {
        if (isset($id) && !empty($id)) {
            $user = ModelFactory::getModel("User")->readData($this->toString($id));
        } else {
            $session = $this->getSession();
            $user = $session["user"];
        }       
        return $user;
    }

    /**
     * Checks if logged User is Admin
     * @return bool
     */
    public function isAdmin() {
        if ($this->isLogged() && $this->getUser()['role'] === "1"){
            return true;
        }
    }
}