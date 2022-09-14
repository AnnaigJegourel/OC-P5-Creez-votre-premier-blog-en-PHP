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
     * Returns the comments of a post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function getComments()
    {
        return ModelFactory::getModel("Comment")->listData($this->getId(), "post_id");
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
        $id = self::getId();   
        if (!isset($id)) 
        {
            $id = "1";
        }

        $post = ModelFactory::getModel("Post")->readData(strval($id));
        $author = $this->getUser($post["user_id"]);
        $session = $this->getSession();
        $allComments = self::getComments();

        return $this->twig->render("post.twig", [
            "post" => $post,
            "author" => $author,
            "session" => $session,
            "allComments" => $allComments
        ]);
    }

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
            $id = self::getId();   
            if (!isset($id)) 
            {
                $message = "Vous devez être connecté(e) comme admin pour modifier un article.";                
            
                return $this->twig->render("message.twig", ["message" => $message]);
            }
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
        $post_id = self::getId();
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
            $id = $this->getId();   
            ModelFactory::getModel("Comment")->deleteData(strval($id), "post_id");
            ModelFactory::getModel("Post")->deleteData(strval($id));
            $message = "Votre article a bien été supprimé.";                
            
            return $this->twig->render("message.twig", ["message" => $message]);
        } else {
            $message = "Vous ne disposez pas des droits pour supprimer un article.";

            return $this->twig->render("message.twig", ["message" => $message]);
        }
    }
}