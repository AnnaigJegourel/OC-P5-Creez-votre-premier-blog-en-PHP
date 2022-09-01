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
        $session = $this->getSession();

        return $this->twig->render("post.twig", [
            "post" => $post,
            "session" => $session
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

        $date_created = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date_created = $date_created->format('Y-m-d H:i:s');

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
     * Renders the view update post form
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updatepostformMethod()
    {
        $id = filter_input(INPUT_GET, "id");   
        if (!isset($id)) 
        {
            return $this->twig->render("error.twig");
        }

        $post = $post = ModelFactory::getModel("Post")->readData(strval($id));
        return $this->twig->render("postupdate.twig",["post" => $post]);
    }

    public function postupdateMethod()
    {
        $title = htmlspecialchars($_POST['title']);
        $intro = htmlspecialchars($_POST['intro']);
        $content = htmlspecialchars($_POST['content']);

        $date_updated = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date_updated = $date_updated->format('Y-m-d H:i:s');

        $date_created = "2025-08-22";

        $user_id = 1;
                
        $data = [
            'title' => $title,
            'intro' => $intro,
            'content' => $content,
            'date_created' => $date_created,
            'date_updated' => $date_updated,
            'user_id' => $user_id
        ];
        
        $post_id = filter_input(INPUT_POST, "id");
        $post = ModelFactory::getModel('Post')->updateData(strval($post_id), $data);

        return $this->twig->render("updated.twig", ["post" => $post]);
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
        $id = filter_input(INPUT_GET, "id");   

        ModelFactory::getModel("Post")->deleteData(strval($id));

        return $this->twig->render("deleted.twig");
    }

}