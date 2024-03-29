<?php

namespace App\Model\Factory;

use PDO;

/**
 * Class PdoFactory
 * 
 * Creates the connection if it doesn't exist
 * 
 * @package App\Model
 */
class PdoFactory
{
    /**
     * Stores the connection
     * 
     * @var null
     */
    private static $pdo = null;

    /**
     * Creates the connection if it doesn't exist & returns it 
     * 
     * @return PDO|null
     */
    public static function getPDO()
    {
        if  (self::$pdo === null) {
            self::$pdo = new PDO(DB_DSN, DB_USER, DB_PASS, DB_OPTIONS);
            self::$pdo->exec("SET NAMES UTF8");
        }

        return self::$pdo;
    }
}