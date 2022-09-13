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



    /* ***************** READ ***************** */

    /**
     * Renders the View of Users list (read all Users)
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readAllUsersMethod()
    {
        if ($this->isAdmin()) {
            $allUsers = $this->getAllUsers();

            return $this->twig->render("userslist.twig", ["allUsers" => $allUsers]);    
        } else {
            $message = "Vous n'avez pas accès à la liste des utilisateurs du site.";
            return $this->twig->render("error.twig", ["message" => $message]);
        }
    }



    /**
     * Renders the View Profile (read single User)
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readUserMethod()
    {
        $id = $this->getUserId();

        if (!isset($id) || empty($id)) 
        {
            $message = "Aucun identifiant n'a été trouvé. Essayez de vous (re)connecter.";
            return $this->twig->render("error.twig", ["message" => $message]);
        } else {
            $user = ModelFactory::getModel("User")->readData(strval($id));
            return $this->twig->render("profile.twig", ["user" => $user]);    
        }
    }


    /* ***************** CREATE ***************** */
    /**
     * Renders the view of the form to create a user account
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createuserformMethod()
    {
        return $this->twig->render("usercreate.twig");
    }

    /**
     * Manages user account creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function usercreateMethod()
    {
        $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_created = $date_created->format("Y-m-d H:i:s");
        
        $data = [
            "name" => $this->getPost()["name"],
            "email" => $this->getPost()["email"],
            "password" => $this->getPost()["password"],
            "date_created" => $date_created,
            "role" => "0"
        ];
        
        ModelFactory::getModel("User")->createData($data);

        $message = "Félicitations! Votre compte a bien été créé. Connectez-vous pour commenter les articles.";

        return $this->twig->render("created.twig", ["message" => $message]);
    }


    /* ***************** UPDATE ***************** */
    /**
     * Renders the view update user form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateuserformMethod()
    {
        $id = $this->getUserId();

        if (!isset($id)) 
        {
            $message = "Aucun identifiant n'a été trouvé. Essayez de vous (re)connecter.";
            return $this->twig->render("error.twig", ["message" => $message]);
        }

        $user = ModelFactory::getModel("User")->readData(strval($id));
        return $this->twig->render("userupdate.twig",["user" => $user]);
    }

    public function userupdateMethod()
    {
        $session = $this->getSession();
        $user = $session['user'];
        $user_id = $user['id'];

        /*$date_updated = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_updated = $date_updated->format("Y-m-d H:i:s");*/
                
        $data = [
            "name" => $this->getPost()["name"],
            "email" => $this->getPost()["email"],
            "password" => $this->getPost()["password"],
            /*"date_updated" => $date_updated*/
        ];
        
        ModelFactory::getModel("User")->updateData(strval($user_id), $data);

        $message = "Votre profil a bien été modifié.";
        return $this->twig->render("updated.twig", ["message" => $message]);
    }


    /* ***************** DELETE ***************** */
    /**
     * Deletes a User Account
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function userdeleteMethod()
    {
        $session = $this->getSession();
        $user = $session['user'];
        $user_id = $user['id'];
    
        ModelFactory::getModel("User")->deleteData(strval($user_id));

        $message = "Votre compte a bien été supprimé.";
        return $this->twig->render("deleted.twig", ["message" => $message]);
    }


    /* ***************** LOG ***************** */
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


    /**
     * Logs out user
     *
     * @return void
     */
    public function logoutMethod()
    {
        setcookie("PHPSESSID", "", time() - 3600, "/");
        session_destroy();
        $this->redirect('home');
    }

    /**
     * getLoggedUser
     *
     * @return void
     */
    /*public function isLog()
    {
        if (isset($this->session['user'])) {
            $user = $this->session['user'];
            if (isset($user) && !empty($user)) {
                return $user;
            }
        }
    }*/
    /* faire plutôt une fonction isLogged(): bool?*/




} 