<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserController
 * manages the User functions
 * @package App\Controller
 */
class UserController extends MainController
{
    /**
     * Renders the Login Form View
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        return $this->twig->render("login.twig");
    }

    /**
     * Gets all Users
     * @return array\string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function getAllUsers()
    {
        $allUsers = ModelFactory::getModel("User")->listData();

        return $allUsers;
    }

    /**
     * Renders the View of all Users
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readAllUsersMethod()
    {
        $allUsers = $this->getAllUsers();

        return $this->twig->render("userslist.twig", ["allUsers" => $allUsers]);
    }

        /**
     * Renders the View Profile (single User)
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readUserMethod()
    {
        $session = $this->getSession();
        /*var_dump($session);die();*/
        $user = $session['user'];
        /*var_dump($user);die();*/
        $id = $user['id'];
        /* var_dump($id);die();*/

        if (!isset($id)) 
        {
            $message = "Aucun identifiant n'a été trouvé. Essayez de vous (re)connecter.";
            return $this->twig->render("error.twig", ["message" => $message]);
        } else {
            $user = ModelFactory::getModel("User")->readData(strval($id));
            return $this->twig->render("profile.twig", [
                "user" => $user,
            ]);    
        }
    }


    /**
     * Logs in User & return user data
     * @return array\string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function loginMethod()
    {
        $data = $this->getPost();
        $user = ModelFactory::getModel("User")->readData(strval($data["email"]), "email");

        if(!$user) {
            $message = "L'e-mail saisi n'est pas dans la base de données.";
            return $this->twig->render("error.twig", ["message" => $message]);
        } else {
            if ($data["pwd"] !== $user["password"]) {
                $message = "Il y a une erreur sur le mot de passe.";
                return $this->twig->render("error.twig", ["message" => $message]);
            } else {
                self::createSession($user);
                return $this->twig->render("profile.twig", ["user" => $user]);
            }    
        }
    }


    /**
     * Creates a user session
     *
     * @param  mixed $user
     *
     * @return void
     */
    private function createSession($user)
    {
        $this->session['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password'],
            'date_created' => $user['date_created'],
            'role' => $user['role']
        ];

        $_SESSION['user'] = $this->session['user'];
    }

} 