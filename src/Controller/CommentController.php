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
        if ($this->isLogged()) {
            $user_id = $this->getUserId();
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");
            $data = [
                "author" => $this->putSlashes($this->getPost()["author"]),
                "title" => $this->putSlashes($this->getPost()["title"]),
                "content" => $this->putSlashes($this->getPost()["content"]),
                "post_id" => $this->getId(),
                "date_created" => $date_created,
                "user_id" => $user_id
            ];
            ModelFactory::getModel("Comment")->createData($data);
            $message = "Votre commentaire a bien été créé. Il sera publié une fois approuvé par l'admin.";

            return $this->twig->render("Front/message.twig", ["message" => $message]);
        }else{
            $message = "Vous devez vous connecter pour commenter un article.";

            return $this->twig->render("Front/message.twig", ["message" => $message]);
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
        $comment = ModelFactory::getModel("Comment")->readData($this->toString($comment_id));
        $author_id = $this->toString($comment["user_id"]);

        if($this->isLogged() && ($user_id === $author_id || $this->isAdmin())) {
            ModelFactory::getModel("Comment")->deleteData($this->toString($comment_id));
            $message = "Le commentaire a bien été supprimé.";
            } else {
                $message = "Vous ne pouvez pas supprimer les commentaires créés par d'autres comptes.";
            }
        return $this->twig->render("Front/message.twig", ["message" => $message]);    
    }

    /* ***************** ADMIN ***************** */

    /**
     * Manages Admin's comments choice
     */
    public function approveCommentMethod()
    {
        $choice = $this->getPost()["approve"];
        $data = ["approved" => $this->toInt($choice)];
        $comment_id = $this->getId();

        if ($choice === "1") {
            $message = "Le commentaire a bien été approuvé et publié.";
        } elseif ($choice === "0") {
            $message = "Le commentaire a été refusé et ne sera pas publié.";
        };

        ModelFactory::getModel("Comment")->updateData($this->toString($comment_id), $data);

        return $this->twig->render("Front/message.twig", ["message" => $message]);
    }
}
