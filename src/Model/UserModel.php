<?php

namespace App\Model;

/**
 * Class UserModel
 * Manages User Data
 * @package App\Model
 */
class UserModel extends MainModel {
    /**
     * User Id
     *
     * @var int
     */
    private $id;

    /**
     * User Name
     *
     * @var string
     */
    private $name;

    /**
     * User E-mail
     *
     * @var string
     */
    private $email;

    /**
     * User Password
     *
     * @var string
     */
    private $password;

    /**
     * Date of User Creation
     *
     * @var DateTime        //OK?
     */
    private $date_created;

    /**
     * User Role
     *
     * @var int             //et non bool car en SQL= tinyint (0 ou 1)?
     */
    private $role;

    /**
     * Gets User Id
     * 
     * @return int
     */
	public function getId()
    {
		return $this->id;
	}

    /**
     * Sets User Id
     *
     * @param int $id
     * @return void         //ok car pas de "return"?
     */
	public function setId($id)
    {
		$this->id = $id;
	}

    /**
     * Gets User Name
     *
     * @return string
     */
	public function getName()
    {
		return $this->name;
	}

    /**
     * Sets User Name
     *
     * @param string $name
     * @return void
     */
	public function setName($name)
    {
		$this->name = $name;
	}

    /**
     * Gets User E-mail
     *
     * @return string
     */

	public function getEmail()
    {
		return $this->email;
	}

    /**
     * Sets User E-mail
     *
     * @param string $email
     * @return void
     */
	public function setEmail($email)
    {
		$this->email = $email;
	}

    /**
     * Gets User Password
     *
     * @return string
     */
	public function getPassword()
    {
		return $this->password;
	}

    /**
     * Sets User Password
     *
     * @param string $password
     * @return void
     */
	public function setPassword($password)
    {
		$this->password = $password;
	}

    /**
     * Gets Date of User Creation
     *
     * @return DateTime
     */
	public function getDateCreated()
    {
		return $this->date_created;
	}

    /**
     * Sets Date of User Creation
     *
     * @param DateTime $date_created
     * @return void
     */
	public function setDateCreated($date_created)
    {
		$this->date_created = $date_created;
	}

    /**
     * Gets User Role
     *
     * @return int
     */
	public function getRole()
    {
		return $this->role;
	}

    /**
     * Sets User Role
     *
     * @param int $role
     * @return void
     */
	public function setRole($role)
    {
		$this->role = $role;
	}
}