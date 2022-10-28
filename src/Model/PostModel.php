<?php

namespace App\Model;

/**
 * Class PostModel
 * 
 * Manages Posts Data
 * 
 * @package App\Model
 */
class PostModel extends MainModel {
    /**
     * Post Id
     *
     * @var int
     */
    private $id;

    /**
     * Post Creation Date
     *
     * @var DateTime
     */
    private $date_created;
    
    /**
     * Post Update Date
     *
     * @var DateTime
     */
    private $date_updated;

    /**
     * Post Title
     *
     * @var string
     */
    private $title;

    /**
     * Post Introduction
     *
     * @var string
     */
    private $intro;

    /**
     * Post Content / Text
     *
     * @var string
     */
    private $content;

    /**
     * User id (post author)
     *
     * @var int
     */
    private $user_id;           //pas nécessaire? (clé étrangère)

    /* ************************ GETTERS & SETTERS ************************ */
    /**
     * Gets Post Id
     * 
     * @return int
     */
	public function getId()
    {
		return $this->id;
	}

    /**
     * Sets Post Id
     *
     * @param int $id
     */
	public function setId($id)
    {
		$this->id = $id;
	}

    /**
     * Gets Date of Post Creation
     *
     * @return DateTime
     */
	public function getDateCreated()
    {
		return $this->date_created;
	}

    /**
     * Sets Date of Post Creation
     *
     * @param DateTime $date_created
     */
	public function setDateCreated($date_created)
    {
		$this->date_created = $date_created;
	}

    /**
     * Gets Date of Post Update
     *
     * @return DateTime
     */
	public function getDateUpdated()
    {
		return $this->date_updated;
	}

    /**
     * Sets Date of Post Update
     *
     * @param DateTime $date_updated
     */
	public function setDateUpdated($date_updated)
    {
		$this->date_updated = $date_updated;
	}

    /**
     * Gets Post Title
     *
     * @return string
     */
	public function getTitle()
    {
		return $this->title;
	}

    /**
     * Sets Post Title
     *
     * @param string $title
     */
	public function setTitle($title)
    {
		$this->title = $title;
	}

    /**
     * Gets Post Introduction
     *
     * @return string
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * Sets Post Introduction
     *
     * @param string $intro
     */
	public function setIntro($intro)
    {
		$this->intro = $intro;
	}

    /**
     * Gets Post Content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets Post Content
     *
     * @param string $content
     */
	public function setContent($content)
    {
		$this->content = $content;
	}

    /**
     * Gets User (author) Id
     * 
     * @return int
     */
	public function getUserId()
    {
		return $this->user_id;
	}

    /**
     * Sets User Id
     *
     * @param int $user_id
     */
	public function setUserId($user_id)
    {
		$this->user_id = $user_id;
	}
}