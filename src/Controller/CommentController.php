<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentController extends MainController {

     /**
     * Renders the View Home
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        $this->redirect("home");
    }

    /* ***************** CREATE ***************** */

    /**
     * Manages comment creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createCommentMethod()
    {
        $user_id = $this->getUserId();

        if ($this->isLogged()) {
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");
            $data = [
                "title" => addslashes($this->getPost()["title"]),
                "content" => addslashes($this->getPost()["content"]),
                "author" => addslashes($this->getPost()["author"]),
                "post_id" => $this->getId(),
                "date_created" => $date_created,
                "user_id" => $user_id
            ];
            ModelFactory::getModel("Comment")->createData($data);
            $message = "Votre commentaire a bien été créé. Il sera publié une fois approuvé par l'admin.";

            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }

    /* ***************** READ ***************** */

    /**
     * Renders the View Comments List
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function listCommentsMethod()
    {
        if ($this->isAdmin()) {
            $allComments = ModelFactory::getModel("Comment")->listDataLatest();

            return $this->twig->render("listComments.twig", ["allComments" => $allComments]);
        } else {
            $message = "Vous n'êtes pas autorisé à voir la liste des commentaires";
            
            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }

    /* ***************** DELETE ***************** */

    /**
     * Manages comment delete
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteCommentMethod()
    {
        $user_id = $this->getUserId();
        $comment_id = $this->getId();   
        $comment = ModelFactory::getModel("Comment")->readData(strval($comment_id));
        $author_id = strval($comment["user_id"]);

        if($this->isLogged() && ($user_id === $author_id || $this->isAdmin())) {
            ModelFactory::getModel("Comment")->deleteData(strval($comment_id));
            $message = "Le commentaire a bien été supprimé.";
            } else {
                $message = "Vous ne pouvez pas supprimer les commentaires créés par d'autres comptes.";
            }
        return $this->twig->render("message.twig", ["message" => $message]);    
    }

    /* ***************** ADMIN ***************** */

    /**
     * Manages Admin's comments choice
     */
    public function approveCommentMethod()
    {
        $choice = addslashes($this->getPost()["approve"]);
        $data = ["approved" => intval($choice)];
        $comment_id = $this->getId();

        if ($choice === "1") {
            $message = "Le commentaire a bien été approuvé et publié.";
        } elseif ($choice === "0") {
            $message = "Le commentaire a été refusé et ne sera pas publié.";
        };

        ModelFactory::getModel("Comment")->updateData(strval($comment_id), $data);

        return $this->twig->render("message.twig", ["message" => $message]);
    }
}
