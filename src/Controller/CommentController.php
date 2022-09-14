<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentController extends MainController {

    public function defaultMethod()
    {
        self::redirect("home");
    }

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

        if (!isset($user_id) || empty($user_id)) 
        {
            $message = "Vous devez vous connecter pour écrire un commentaire.";
            return $this->twig->render("error.twig", ["message" => $message]);

        } else {
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");
        
            $data = [
                "title" => addslashes($this->getPost()["title"]),
                "content" => addslashes($this->getPost()["content"]),
                "post_id" => $this->getId(),
                "date_created" => $date_created,
                "user_id" => $user_id
            ];
        
            $comment = ModelFactory::getModel("Comment")->createData($data);

            return $this->twig->render("post.twig", ["comment" => $comment]);
        }
    }

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

        if (!isset($user_id) || empty($user_id)) 
        {
            $message = "Vous devez vous connecter pour supprimer un commentaire.";

            return $this->twig->render("error.twig", ["message" => $message]);
        } else {
            $comment_id = $this->getId();   
            $comment = ModelFactory::getModel("Comment")->readData(strval($comment_id));
            $author_id = strval($comment["user_id"]);

            if ($user_id !== $author_id){
                $message = "Vous ne pouvez pas supprimer les commentaires créés par d'autres comptes.";
                
                return $this->twig->render("error.twig", ["message" => $message]);    
            } else {
                ModelFactory::getModel("Comment")->deleteData(strval($comment_id));

                return $this->twig->render("deleted.twig");
            }
        }
    }

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
            $allComments = ModelFactory::getModel("Comment")->listData();

            return $this->twig->render("commentslist.twig", ["allComments" => $allComments]);
        } else {
            $message = "Vous n'êtes pas autorisé à voir la liste des commentaires";
            
            return $this->twig->render("error.twig", ["message" => $message]);
        }
    }

    /**
     * Manages Admin's comments choice
     */
    public function approveCommentMethod()
    {
        $choice = addslashes($this->getPost()["approve"]);
        $data = ["approved" => intval($choice)];
        $comment_id = $this->getId();

        if ($choice === "1") {
            $approved = "approuvé";
        } elseif ($choice === "2") {
            $approved = "refusé";
        };

        ModelFactory::getModel("Comment")->updateData(strval($comment_id), $data);

        return $this->twig->render("approved.twig", ["approved" => $approved]);
    }
}
