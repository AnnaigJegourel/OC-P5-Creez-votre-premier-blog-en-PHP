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
        /*var_dump($user);die;*/

        if(!$user) {
            $message = "L'e-mail saisi n'est pas dans la base de données.";
            return $this->twig->render("error.twig", ["message" => $message]);
        } else {
            if ($data["pwd"] !== $user["password"]) {
                $message = "Il y a une erreur sur le mot de passe.";
                return $this->twig->render("error.twig", ["message" => $message]);
            } else {
                return $this->twig->render("profile.twig", [
                    "data" => $data,
                    "user" => $user
                ]);
            }    
        }

        /* Pourquoi ça ne marche pas avec getUser() ?? */
        /* $user = self::getUser(strval($data["email"]), "email"); */
        /*         var_dump($user);die(); donne : "bool(false)" */
        /*         tracy : undefined $value / $key + lignes 82/83 dans MainController */

    }
} 