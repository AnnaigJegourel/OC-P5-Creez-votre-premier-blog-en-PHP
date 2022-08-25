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
        $id = filter_input(INPUT_GET, "id");   
        if (!isset($id)) 
        {
            $id = "1";
        }

        $post = ModelFactory::getModel("Post")->readData(strval($id));

        return $this->twig->render("post.twig", ["post" => $post]);
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
        
        $title = htmlspecialchars($_POST['title']);
        $intro = htmlspecialchars($_POST['intro']);
        $content = htmlspecialchars($_POST['content']);
        $date_created = "2022-08-22";
        $user_id = 1;
        
        $data = [
            'title' => $title,
            'intro' => $intro,
            'content' => $content,
            'date_created' => $date_created,
            'user_id' => $user_id
        ];
        
        $newpost = ModelFactory::getModel('Post')->createData($data);

        return $this->twig->render("created.twig", ["newpost" => $newpost]);

    }
    /** 
    * problèmes d'affichage : 
    *          caractères spéciaux, 
    *          datetime-> string :             
    *              'date_created' => new \DateTime('now', new \DateTimeZone('Europe/Paris')),
    *
    * 'id_user' => self::getUserId()?
    */

    /**
     * Deletes a post
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function postdeleteMethod()
    {
        $id = filter_input(INPUT_GET, "id");   

        ModelFactory::getModel("Post")->deleteData(strval($id));

        return $this->twig->render("deleted.twig");
    }

}