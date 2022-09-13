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
        $user_id = $this->getUserId();

        if (!isset($user_id) || empty($user_id)) 
        {
            $message = "Vous devez vous connecter pour écrire un commentaire.";
            return $this->twig->render("error.twig", ["message" => $message]);

        } else {
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");
        
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
    public function commentApproveMethod()
    {
        /*var_dump($this->getPost());die(); */
        /* array(1) { ["approve"]=> string(1) "1" } */

        /* RECUPERER LE CHOIX CLIQUÉ PAR L'ADMIN */
        $choice = $this->getPost()["approve"];
        /*  var_dump($choice);die(); */
        /* string(1) "1" */
        
        /* CONVERTIR CE CHOIX EN INT POUR LA BDD */
        $data = ["approved" => intval($choice)];
        /* var_dump($data); die(); */
        /* array(1) { ["approved"]=> int(1) } */

        /* RECUPÉRER L'ID DU COMMENTAIRE APPROUVÉ OU REFUSÉ */
        $comment_id = $this->getId();

        /* TRADUIRE LE CHOIX EN MOT POUR L'AFFICHAGE DU MESSAGE */
        if ($choice === "1") {
            $approved = "approuvé";
        } elseif ($choice === "2") {
            $approved = "refusé";
        };

        /* MODIFIER L'ATTRIBUT CORRESPONDANT DANS LA BDD */
        ModelFactory::getModel("Comment")->updateData(strval($comment_id), $data);

        /* RENVOYER VERS LA VUE DU MESSAGE DE SUCCÈS */
        return $this->twig->render("approved.twig", ["approved" => $approved]);
    }
}
