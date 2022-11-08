<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;

use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


/**
 * Class PostController
 * 
 * Manages the post page
 * 
 * @package App\Controller
 */
class PostController extends MainController
{
    /* ***************** READ ***************** */
    /**
     * Renders the View Posts List
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function defaultMethod()
    {
        $allPosts = ModelFactory::getModel("Post")->listDataLatest();

        return $this->twig->render("Front/listPosts.twig", ["allPosts" => $allPosts]);
    }

    /**
     * Renders the View Post
     * 
     * @return string
     * 
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

        $post = ModelFactory::getModel("Post")->readData((string) $post_id);
        $author = $this->getUser($post["user_id"]);
        $session = $this->getSession();
        $allComments = ModelFactory::getModel("Comment")->listComments($this->getId(), "post_id");

        return $this->twig->render("Front/post.twig", [
            "post" => $post,
            "author" => $author,
            "session" => $session,
            "allComments" => $allComments
        ]);
    }


    /* ***************** CREATE ***************** */
    /**
     * Manages post creation
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function createPostMethod()
    {
        if($this->isAdmin()){
            $date_created = new \DateTime("now", new \DateTimeZone("Europe/Paris"));
            $date_created = $date_created->format("Y-m-d H:i:s");
            $user_id = $this->getUserId();

            $data = [
                "title" => $this->putSlashes($this->getPost()["title"]),
                "intro" => $this->putSlashes($this->getPost()["intro"]),
                "content" => $this->putSlashes($this->getPost()["content"]),
                "date_created" => $date_created,
                "date_updated" => $date_created,
                "user_id" => $user_id
            ];

            ModelFactory::getModel("Post")->createData($data);
            $message = "L'article a bien été créé.";

        } else {
            $message = "Vous devez vous connecter en tant qu'admin pour créer un article.";
        }
        
        $this->setMessage($message);
        $this->redirect("post");
    }

    /* ***************** UPDATE ***************** */
    /**
     * Renders the view update post form
     * 
     * @return string
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatePostFormMethod()
    {
        if ($this->isAdmin()) {
            $post_id = $this->getId();
            $post = ModelFactory::getModel("Post")->readData((string)$post_id);

            return $this->twig->render("Back/updatePost.twig",["post" => $post]);
        } else {
            $message = "Vous devez être connecté(e) en tant qu'admin pour consulter cette page.";
            $this->setMessage($message);
            $this->redirect("user");
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
            "title" => $this->putSlashes($this->getPost()["title"]),
            "intro" => $this->putSlashes($this->getPost()["intro"]),
            "content" => $this->putSlashes($this->getPost()["content"]),
            "date_updated" => $date_updated
        ];

        ModelFactory::getModel("Post")->updateData((string) $post_id, $data);
        $message = "Votre article a bien été modifié.";

        $this->setMessage($message);
        $this->redirect("post");
    }

    /* ***************** DELETE ***************** */
    /**
     * Deletes a post
     * 
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deletePostMethod()
    {
        $post_id = (string) $this->getId();
        ModelFactory::getModel("Comment")->deleteData($post_id, "post_id");
        ModelFactory::getModel("Post")->deleteData($post_id);
        $message = "L'article a bien été supprimé.";

        $this->setMessage($message);
        $this->redirect("post");
    }
}