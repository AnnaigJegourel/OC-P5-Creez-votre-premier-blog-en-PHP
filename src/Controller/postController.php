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
        $session = $this->getSession();
        $allComments = self::getComments();

        return $this->twig->render("post.twig", [
            "post" => $post,
            "session" => $session,
            "allComments" => $allComments
        ]);
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
        return ModelFactory::getModel("Comment")->listData(self::getId(), 'post_id');
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
        return $this->twig->render("postcreate.twig");
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
        $date_created = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date_created = $date_created->format('Y-m-d H:i:s');

        $user_id = 1;
        
        $data = [
            'title' => self::getPost()['title'],
            'intro' => self::getPost()['intro'],
            'content' => self::getPost()['content'],
            'date_created' => $date_created,
            'user_id' => $user_id
        ];
        
        ModelFactory::getModel('Post')->createData($data);
        /*$id = self::getId();   */

        return $this->twig->render("created.twig", [
            "newpost" => $data
            /*"post_id" => $id*/
        ]);
    }
    /** 
    * problèmes d'affichage : apostrophe saisie dans form -> erreur 
    * 'id_user' => self::getUserId()?
    */

    /**
     * Renders the view update post form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatepostformMethod()
    {
        $id = self::getId();   
        if (!isset($id)) 
        {
            return $this->twig->render("error.twig");
        }

        $post = ModelFactory::getModel("Post")->readData(strval($id));
        return $this->twig->render("postupdate.twig",["post" => $post]);
    }

    public function postupdateMethod()
    {
        $post_id = self::getId();

        $date_updated = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date_updated = $date_updated->format('Y-m-d H:i:s');
                
        $data = [
            'title' => self::getPost()['title'],
            'intro' => self::getPost()['intro'],
            'content' => self::getPost()['content'],
            'date_updated' => $date_updated
        ];
        
        ModelFactory::getModel('Post')->updateData(strval($post_id), $data);

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
        $id = self::getId();   

        ModelFactory::getModel("Comment")->deleteData(strval($id), "post_id");
    
        ModelFactory::getModel("Post")->deleteData(strval($id));

        return $this->twig->render("deleted.twig");
    }

}