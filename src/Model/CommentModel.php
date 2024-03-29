<?php

namespace App\Model;


/**
 * Class CommentModel
 * 
 * Manages Comments Data
 * 
 * @package App\Model
 */
class CommentModel extends MainModel
{
    /**
     * Comment Id
     *
     * @var int
     */
    private $id;

    /**
     * Comment Creation Date
     *
     * @var DateTime
     */
    private $date_created;

    /**
     * Comment Content
     *
     * @var string
     */
    private $content;

    /**
     * Comment status
     *
     * @var bool
     */
    private $approved;

    /**
     * Comment User (author) id
     *
     * @var int
     */
    private $user_id;

    /**
     * Id of the commented post
     *
     * @var int
     */
    private $post_id;

    /**
     * Lists all comments about a post
     *
     * @param string|null $value
     * @param string|null $key
     * @return array|mixed
     */
    public function listComments(string $value = null, string $key = null)
    {
        if (isset($key)) {
            $query = "SELECT Comment.id, Comment.date_created, content, approved, user_id, post_id, name
                    FROM Comment INNER JOIN User ON Comment.user_id = User.id
                    WHERE " . $key . " = ? 
                    ORDER BY Comment.date_created DESC";

            return $this->database->getAllData($query, [$value]);
        }

        $query = "SELECT Comment.id, Comment.date_created, content, approved, user_id, post_id, name
                FROM Comment INNER JOIN User ON Comment.user_id = User.id
                ORDER BY Comment.date_created DESC";
    
        return $this->database->getAllData($query);
    }

    /* ************************ GETTERS & SETTERS ************************ */
    /**
     * Gets the Comment Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the Comment Id
     *
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Gets the date of comment creation
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Sets the date of comment creation
     *
     * @param DateTime $date_created
     */
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }

    /**
     * Gets the content of a comment
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content of a comment
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Gets comment status (approved=1 or not=0)
     *
     * @return bool
     */     
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Sets comment status
     *
     * @param bool $approved
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;
    }

    /**
     * Gets Id of the author (User id)
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Sets Id of the author (User id)
     *
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Gets id of the commented post
     *
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * Sets id of the commented post
     *
     * @param int $post_id
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;
    }
}