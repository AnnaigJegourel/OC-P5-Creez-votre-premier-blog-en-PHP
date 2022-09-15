<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Class PostController
 * manages the post page
 * @package App\Controller
 */
class PostController extends MainController
{
    /* ***************** READ ***************** */

    /**
     * Renders the View Posts List
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        $allPosts = ModelFactory::getModel("Post")->listData();

        return $this->twig->render("listPosts.twig", ["allPosts" => $allPosts]);
    }

    /**
     * Renders the View Post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readPostMethod()
    {
        $post_id = $this->getId();   
        if (!isset($post_id)) 
        {
            $post_id = "1";
        }

        $post = ModelFactory::getModel("Post")->readData(strval($post_id));
        $author = $this->getUser($post["user_id"]);
        $session = $this->getSession();
        $allComments = $this->getComments();

        return $this->twig->render("post.twig", [
            "post" => $post,
            "author" => $author,
            "session" => $session,
            "allComments" => $allComments
        ]);
    }

    /* ***************** CREATE ***************** */

    /**
     * Renders the view create post form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createPostFormMethod()
    {
        if ($this->isAdmin()) {

            return $this->twig->render("createPost.twig");
        } else {
            $message = "Vous ne disposez pas des droits pour créer un article.";                
            
            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }

    /**
     * Manages post creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createPostMethod()
    {
        $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_created = $date_created->format("Y-m-d H:i:s");
        $user_id = $this->getUserId();

        $data = [
            "title" => addslashes($this->getPost()["title"]),
            "intro" => addslashes($this->getPost()["intro"]),
            "content" => addslashes($this->getPost()["content"]),
            "date_created" => $date_created,
            "user_id" => $user_id
        ];
        
        ModelFactory::getModel("Post")->createData($data);
        $message = "Votre article a bien été créé.";                
            
        return $this->twig->render("message.twig", ["message" => $message]);
    }

    /* ***************** UPDATE ***************** */

    /**
     * Renders the view update post form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatePostFormMethod()
    {
        if ($this->isAdmin()) {
            $id = $this->getId();   
            $post = ModelFactory::getModel("Post")->readData(strval($id));

            return $this->twig->render("updatePost.twig",["post" => $post]);
        } else {
            $message = "Vous ne disposez pas des droits pour modifier un article.";      
            
            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }

    /**
     * Manages post update
     */
    public function updatePostMethod()
    {
        $post_id = $this->getId();
        $date_updated = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
        $date_updated = $date_updated->format("Y-m-d H:i:s");
                
        $data = [
            "title" => addslashes($this->getPost()["title"]),
            "intro" => addslashes($this->getPost()["intro"]),
            "content" => addslashes($this->getPost()["content"]),
            "date_updated" => $date_updated
        ];
        
        ModelFactory::getModel("Post")->updateData(strval($post_id), $data);
        $message = "Votre article a bien été modifié.";                
            
        return $this->twig->render("message.twig", ["message" => $message]);
    }

    /* ***************** DELETE ***************** */

    /**
     * Deletes a post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deletePostMethod()
    {
        if($this->isAdmin()) {
            $post_id = $this->getId();   
            ModelFactory::getModel("Comment")->deleteData(strval($post_id), "post_id");
            ModelFactory::getModel("Post")->deleteData(strval($post_id));
            $message = "L'article a bien été supprimé.";                
            
            return $this->twig->render("message.twig", ["message" => $message]);
        } else {
            $message = "Vous ne disposez pas des droits pour supprimer un article.";

            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }
}