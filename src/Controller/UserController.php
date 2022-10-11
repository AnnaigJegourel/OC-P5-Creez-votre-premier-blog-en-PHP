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
        return $this->twig->render("Front/login.twig");
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

    /* ***************** LOG & ADMIN ***************** */
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
     * Logs in User & return user data
     * @return array\string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function loginMethod()
    {
        $data = $this->getPost();
        $user = ModelFactory::getModel("User")->readData($this->toString($data["email"]), "email");

        /*$inputHash = password_hash($data["pwd"], PASSWORD_DEFAULT);
        $bdHash = $user["password"];
        var_dump($inputHash);
        var_dump($bdHash);die();*/


        /* V1 : avec hash et password_verify() */
        if(isset($user) && !empty($user) && password_verify($data["pwd"], $user["password"])) {
            $this->createSession($user);
            return $this->twig->render("Front/profile.twig", ["user" => $user]);
        } else {
            $message = "L'e-mail ou le mot de passe est erroné.";
            return $this->twig->render("Front/message.twig", ["message" => $message]);
        }


        /* V0: sans hash
        if(isset($user) && !empty($user) && $data["pwd"] === $user["password"]) {
            $this->createSession($user);
            return $this->twig->render("Front/profile.twig", ["user" => $user]);
        } else {
            $message = "L'e-mail ou le mot de passe est erroné.";
            return $this->twig->render("Front/message.twig", ["message" => $message]);
        }*/


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
        $this->redirect("Front/home");
    }


    /* ***************** READ ***************** */

    public function adminMethod()
    {
        return $this->twig->render("Back/admin.twig", [
            "allPosts" => ModelFactory::getModel("Post")->listDataLatest(),
            "allComments" => ModelFactory::getModel("Comment")->listDataLatest(),
            "allUsers" => $this->getAllUsers()
        ]);
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

            return $this->twig->render("Front/message.twig", ["message" => $message]);
        } else {
            $user = ModelFactory::getModel("User")->readData($this->toString($id));

            return $this->twig->render("Front/profile.twig", ["user" => $user]);    
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
    public function createUserFormMethod()
    {
        return $this->twig->render("Front/createUser.twig");
    }

    /**
     * Manages user account creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createUserMethod()
    {
        $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_created = $date_created->format("Y-m-d H:i:s");
        $password = $this->putSlashes($this->getPost()["password"]);
        $password = password_hash($this->password, PASSWORD_DEFAULT);
        //var_dump($password);die();
        
        $data = [
            "name" => $this->putSlashes($this->getPost()["name"]),
            "email" => $this->putSlashes($this->getPost()["email"]),
            "password" => $password,
            "date_created" => $date_created,
            "role" => "0"
        ];
        
        ModelFactory::getModel("User")->createData($data);
        $message = "Félicitations! Votre compte a bien été créé. Connectez-vous pour commenter les articles.";

        return $this->twig->render("Front/message.twig", ["message" => $message]);
    }

    /* ***************** UPDATE ***************** */
    /**
     * Renders the view update user form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateUserFormMethod()
    {
        $user_id = $this->getUserId();
        if (!isset($user_id)) 
        {
            $message = "Aucun identifiant n'a été trouvé. Essayez de vous (re)connecter.";
            
            return $this->twig->render("Front/message.twig", ["message" => $message]);
        }
        $user = ModelFactory::getModel("User")->readData($this->toString($user_id));

        return $this->twig->render("Front/updateUser.twig",["user" => $user]);
    }

    /**
     * Manages update user form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateUserMethod()
    {
        $user_id = $this->getUserId();

        $data = [
            "name" => $this->getPost()["name"],
            "email" => $this->getPost()["email"],
            "password" => $this->getPost()["password"],
        ];
        
        ModelFactory::getModel("User")->updateData($this->toString($user_id), $data);
        $message = "Le profil a bien été modifié.";

        return $this->twig->render("Front/message.twig", ["message" => $message]);
    }

    /* ***************** DELETE ***************** */
    /**
     * Deletes a User Account
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteUserMethod()
    {
        $user_id = $this->getUserId();
        ModelFactory::getModel("Comment")->deleteData($this->toString($user_id), "user_id");
        ModelFactory::getModel("User")->deleteData($this->toString($user_id));
        $this->logoutMethod();
        $message = "Le compte a bien été supprimé.";

        return $this->twig->render("Front/message.twig", ["message" => $message]);
    }
} 