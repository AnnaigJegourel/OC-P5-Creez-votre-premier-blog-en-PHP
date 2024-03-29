<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class UserController
 * 
 * Manages the User functions
 * 
 * @package App\Controller
 */
class UserController extends MainController
{
    /**
     * Logs in User & returns user data OR redirect to login form
     * 
     * @return array\string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {        
        if(null !== $this->getPost() && !empty($this->getPost())) {
            $data = $this->getPost();
            $user = ModelFactory::getModel("User")->readData((string) $data["email"], "email");

            if(isset($user) && !empty($user)){

                if(password_verify($data["pwd"], $user["password"])){
                    $this->createSession($user);

                    return $this->twig->render("Front/profile.twig", ["user" => $user]);
                } else {
                    $message = "Le mot de passe est erroné.";
                    $this->setMessage($message);
                    $this->redirect("user");
                }

            } else {
                $message = "L'e-mail est erroné.";
                $this->setMessage($message);
                $this->redirect("user");
            }

        } else {
            return $this->twig->render("Front/login.twig");
        }
    }

    /**
     * Logs out user
     *
     */
    public function logoutMethod()
    {
        setcookie("PHPSESSID", "", time() - 3600, "/");
        session_destroy();
        $this->redirect("home");
    }

    /**
     * Gets all Users
     * 
     * @return array\string
     * 
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
     * Creates a user session
     * 
     * @param  mixed $user
     */
    private function createSession($user)
    {
        $this->session["user"] = [
            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "password" => $user["password"],
            "date_created" => $user["date_created"],
            "role" => $user["role"]
        ];

        $_SESSION["user"] = $this->session["user"];
    }

    /* ***************** CRUD ***************** */
    /**
     * Returns the data for site administration
     * 
     * @return array|string
     */
    public function adminMethod()
    {
        if($this->isAdmin()){
            return $this->twig->render("Back/admin.twig", [
                "allPosts" => ModelFactory::getModel("Post")->listDataLatest(),
                "allComments" => ModelFactory::getModel("Comment")->listComments(),
                "allUsers" => $this->getAllUsers()
            ]);
        } else {
            $message = "Vous devez être connecté(e) en tant qu'admin pour consulter cette page.";
            $this->setMessage($message);
            $this->redirect("user");
        }
    }

    /**
     * Renders the View Profile (read single User)
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readUserMethod()
    {
        if($this->isLogged()){
            $id = $this->getUserId();
            $user = ModelFactory::getModel("User")->readData((string) $id);
    
            return $this->twig->render("Front/profile.twig", ["user" => $user]);    
        } else {
            $message = "Vous devez vous connecter pour consulter une page de profil.";
            $this->setMessage($message);

            $this->redirect("user");
        }
    }

    /**
     * Manages user account creation
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createUserMethod()
    {
        if (!$this->isLogged()) {

            if(null !== $this->getPost() && $this->checkArray($this->getPost())) {

                $allUsers = ModelFactory::getModel("User")->listData();
                $email = (string) $this->putSlashes($this->getPost()["email"]);

                if(!$this->checkArrayElement($allUsers, "email", $email)){
                    $name = (string) $this->putSlashes($this->getPost()["name"]);

                    if(!$this->checkArrayElement($allUsers, "name", $name)){
                        $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
                        $date_created = $date_created->format("Y-m-d H:i:s");
                        $password = password_hash($this->getPost()["password"], PASSWORD_DEFAULT);
        
                        $data = [
                            "name" => $name,
                            "email" => $email,
                            "password" => $password,
                            "date_created" => $date_created,
                            "role" => "0"
                        ];
        
                        ModelFactory::getModel("User")->createData($data);
                        $message = "Félicitations! Votre compte a bien été créé. Connectez-vous pour commenter les articles.";
                        $this->setMessage($message);
        
                        $this->redirect("user");

                    } else {
                        $message = "Un compte à ce nom existe déjà, veuillez en saisir un autre.";
                        $this->setMessage($message);
    
                        $this->redirect("user!createUser");            
                    }
                } else {
                    $message = "Un compte avec cet e-mail existe déjà, veuillez en saisir un autre.";
                    $this->setMessage($message);

                    $this->redirect("user!createUser");        
                }

            } else {

                return $this->twig->render("Front/createUser.twig");
            }
        } else {
            $message = "Vous ne pouvez pas créer de compte lorsque vous êtes connecté(e).";
            $this->setMessage($message);

            $this->redirect("user!readUser");
        }
    }

    /**
     * Renders the view update user form
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateUserFormMethod()
    {
        if($this->isLogged()){
            $user_id = $this->getUserId();
                $user = ModelFactory::getModel("User")->readData((string) $user_id);
    
                return $this->twig->render("Front/updateUser.twig",["user" => $user]);        

        } else {
            $message = "Vous devez vous connecter pour modifier votre profil.";

            $this->setMessage($message);
            $this->redirect("user");    
        }
    }

    /**
     * Manages update user form
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateUserMethod()
    {
        if($this->isLogged()){

            if(null !== $this->getPost() && $this->checkArray($this->getPost())) {
                $user_id = $this->getUserId();
                $data = [
                    "name" => (string) $this->getPost()["name"],
                    "email" => (string) $this->getPost()["email"],
                ];

                ModelFactory::getModel("User")->updateData((string) $user_id, $data);

                $message = "Le profil a bien été modifié.";
                $this->setMessage($message);
                $this->redirect("user!readUser");

            } else {
                $message = "Vous devez remplir tous les champs du formulaire.";
                $this->setMessage($message);
                $this->redirect("user!updateUserForm");    
            }

        } else {
            $message = "Vous devez vous connecter pour modifier votre profil.";
            $this->setMessage($message);
            $this->redirect("user");
        }
    }

    /**
     * Manages password update form
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatePasswordMethod()
    {
        if($this->isLogged()){

            if(null !== $this->getPost() && $this->checkArray($this->getPost())) {
                $user_id = $this->getUserId();
                $password = password_hash($this->getPost()["password"], PASSWORD_DEFAULT);

                $data = [
                    "password" => $password,
                ];
                ModelFactory::getModel("User")->updateData((string) $user_id, $data);
                $message = "Le mot de passe a bien été modifié.";
            
            } else {
                $message = "Vous devez remplir le champ du formulaire.";
            }

            $this->setMessage($message);
            $this->redirect("user!readUser");

        } else {
            $message = "Vous devez vous connecter pour modifier votre mot de passe.";

            $this->setMessage($message);
            $this->redirect("user");    
        }
    }

    /**
     * Deletes a User Account
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteUserMethod()
    {
        $user_id = (string) $this->getUserId();
        ModelFactory::getModel("Comment")->deleteData($user_id, "user_id");
        ModelFactory::getModel("User")->deleteData($user_id);

        $this->logoutMethod();
    }
}