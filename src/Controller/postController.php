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

        return $this->twig->render("postslist.twig", ["allPosts" => $allPosts]);
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
    public function singlepostMethod()
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
    public function createpostformMethod()
    {
        if ($this->isAdmin()) {
            return $this->twig->render("postcreate.twig");
        } else {
            $message = "Vous ne disposez pas des droits pour créer un article.";                return $this->twig->render("error.twig", ["message" => $message]);
        }
    }

    /**
     * Manages post creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postcreateMethod()
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

        return $this->twig->render("created.twig", ["newpost" => $data]);
    }

    /**
     * Renders the view update post form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatepostformMethod()
    {
        if ($this->isAdmin()) {
            $id = self::getId();   
            if (!isset($id)) 
            {
                return $this->twig->render("error.twig");
            }
    
            $post = ModelFactory::getModel("Post")->readData(strval($id));
            return $this->twig->render("postupdate.twig",["post" => $post]);

        } else {
            $message = "Vous ne disposez pas des droits pour modifier un article.";                return $this->twig->render("error.twig", ["message" => $message]);
        }
    }

    public function postupdateMethod()
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

        return $this->twig->render("updated.twig", [
            "post" => $data,
            "post_id" => $post_id
        ]);
    }

    /**
     * Deletes a post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postdeleteMethod()
    {
        if($this->isAdmin()) {
            $id = $this->getId();   
            ModelFactory::getModel("Comment")->deleteData(strval($id), "post_id");
            ModelFactory::getModel("Post")->deleteData(strval($id));
    
            return $this->twig->render("deleted.twig");
            
        } else {
            $message = "Vous ne disposez pas des droits pour supprimer un article.";                return $this->twig->render("error.twig", ["message" => $message]);
        }
    }
}