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
        /* RECUP L'ID DU USER CONNECTÉ */
        $user_id = $this->getUserId();
        /*var_dump($user_id);die();*/
        /* string = "1" */

        /* VERIF SI QQUN EST CONNECTÉ */
        /* SI NON : MESSAGE D'ERREUR */
        if (!isset($user_id) || empty($user_id)) 
        {
            $message = "Vous devez vous connecter pour supprimer un commentaire.";
            return $this->twig->render("error.twig", ["message" => $message]);
        /* SI UN USER EST CONNECTÉ : */
        } else {
            /* RECUP */
            /* récup l'id du commentaire à supprimer */
            $comment_id = $this->getId();   

            /* récup l'id de l'auteur du commentaire à supprimer */
            /*$author_id = getComment($comment_id)[$user_id];*/
            $comment = ModelFactory::getModel("Comment")->readData(strval($comment_id));
            $author_id = strval($comment["user_id"]);
            /*var_dump($author_id);die();*/
            /* int = 1 */

            /* COMPARE */
            /* si le User connecté n'est pas l'auteur du comm */
            if ($user_id !== $author_id){
                $message = "Vous ne pouvez pas supprimer les commentaires créés par d'autres comptes.";
                return $this->twig->render("error.twig", ["message" => $message]);    
            
             /* si le User connecté est bien l'auteur du comm */
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
