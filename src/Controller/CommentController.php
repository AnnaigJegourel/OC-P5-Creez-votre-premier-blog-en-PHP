<?php

namespace App\Controller;

use App\Model\Factory\ModelFactory;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CommentController extends MainController {

    /*public function defaultMethod()
    {
        $allComments = ModelFactory::getModel("Comment")->listData();

        return $this->twig->render("#", ["allComments" => $allComments]);
    }*/

    /**
     * Manages comment creation
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function commentcreateMethod()
    {
        $date_created = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date_created = $date_created->format('Y-m-d H:i:s');

        $user_id = 1;
        
        $data = [
            'title' => htmlspecialchars($_POST['title']),
            'content' => htmlspecialchars($_POST['content']),
            'post_id' => self::getPostId(),
            'date_created' => $date_created,
            'user_id' => $user_id
        ];
        
        $comment = ModelFactory::getModel('Comment')->createData($data);

        return $this->twig->render("post.twig", ["comment" => $comment]);
    }

    public function getPostId()
    {
        return filter_input(INPUT_GET, 'id');
    }

    public function commentdeleteMethod()
    {
        $id = filter_input(INPUT_GET, "id");   

        ModelFactory::getModel("Comment")->deleteData(strval($id));

        return $this->twig->render("deleted.twig");
    }


}
