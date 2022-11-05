<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class CommentController
 * 
 * Manages the Comments Features
 * 
 * @package App\Controller
 */
class CommentController extends MainController
{
    /**
     * Renders the View Home
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        $this->redirect("home");
    }

    /**
     * Manages comment creation
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createCommentMethod()
    {
        if ($this->isLogged()) {
            $user_id = $this->getUserId();
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");

            $data = [
                "content" => $this->putSlashes($this->getPost()["content"]),
                "post_id" => $this->getId(),
                "date_created" => $date_created,
                "user_id" => $user_id
            ];

            ModelFactory::getModel("Comment")->createData($data);
            $message = "Votre commentaire a bien été créé. Il sera publié une fois approuvé par l'admin.";

        } else {
            $message = "Vous devez vous connecter pour commenter un article.";
        }

        $this->setMessage($message);
        $this->redirect("post");
    }

    /**
     * Manages comment delete
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteCommentMethod()
    {
        $user_id = $this->getUserId();
        $comment_id = (string) $this->getId();
        $comment = ModelFactory::getModel("Comment")->readData($comment_id);
        $author_id = (string) $comment["user_id"];

        if($this->isLogged() && ($user_id === $author_id || $this->isAdmin())) {
            ModelFactory::getModel("Comment")->deleteData($comment_id);
            $message = "Le commentaire a bien été supprimé.";
        } else {
            $message = "Vous ne pouvez pas supprimer les commentaires créés par d'autres comptes.";
        }

        $this->setMessage($message);
        $this->redirect("post");
    }

    /**
     * Manages Admin's comments choice
     */
    public function approveCommentMethod()
    {        
        $choice = $this->getGet()["approve"];
        $data = ["approved" => (int) $choice]; 
        $comment_id = (string) $this->getId();

        if ($choice === "1") {
            $message = "Le commentaire a bien été approuvé et publié.";
        } elseif ($choice === "0") {
            $message = "Le commentaire a été refusé et ne sera pas publié.";
        };

        ModelFactory::getModel("Comment")->updateData($comment_id, $data);
        $this->setMessage($message);
        $this->redirect("user!admin");
    }
}
