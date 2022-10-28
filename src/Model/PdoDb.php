<?php

namespace App\Model;

use PDO;

/**
 * Class PdoDb
 * 
 * Prepares queries before execution & return
 * 
 * @package App\Model
 */
class PdoDb
{
    /**
     * PDO connection
     * 
     * @var PDO
     */
    private $pdo = null;

    /**
     * PdoDb constructor
     * 
     * Receives the PDO connection & store it
     * 
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Returns a unique result form the database
     * 
     * @param string $query
     * @param array $params
     * 
     * @return mixed
     */
    public function getData(string $query, array $params = [])
    {
        $PDOStatement = $this->pdo->prepare($query);
        $PDOStatement->execute($params);

        return $PDOStatement->fetch();
    }

    /**
     * Returns several results from the database
     * 
     * @param string $query
     * @param array $params
     * 
     * @return array|mixed
     */
    public function getAllData(string $query, array $params = [])
    {
        $PDOStatement = $this->pdo->prepare($query);
        $PDOStatement->execute($params);

        return $PDOStatement->fetchAll();
    }

    /**
     * Executes an action to the database
     * 
     * @param string $query
     * @param array $params
     * 
     * @return bool|mixed
     */
    public function setData(string $query, array $params = [])
    {
        $PDOStatement = $this->pdo->prepare($query);

        return $PDOStatement->execute($params);
    }
}