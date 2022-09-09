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
    public function commentcreateMethod()
    {
        $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_created = $date_created->format("Y-m-d H:i:s");
        
        /* getUserId */
        $user_id = 1;
        
        $data = [
            "title" => $this->getPost()["title"],
            "content" => $this->getPost()["content"],
            "post_id" => $this->getId(),
            "date_created" => $date_created,
            "user_id" => $user_id
        ];
        
        $comment = ModelFactory::getModel("Comment")->createData($data);

        return $this->twig->render("post.twig", ["comment" => $comment]);
    }

    
    public function commentdeleteMethod()
    {
        $id = $this->getId();   

        ModelFactory::getModel("Comment")->deleteData(strval($id));

        return $this->twig->render("deleted.twig");
    }


    /**
     * Renders the View Comments List
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function commentslistMethod()
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
    public function commentapproveMethod()
    {
        $choice = $this->getPost()["value"];
        var_dump($choice);die;
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
