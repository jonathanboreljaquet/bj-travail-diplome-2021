<?php
/**
 * DatabaseConnector.php
 *
 * Class allowing the connection to the database with the environment variables of the .env file.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\System;

class DatabaseConnector {

    private $dbConnection = null;

    /**
     * 
     * Constructor of the User object.
     * 
     */
    public function __construct()
    {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db   = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');

        try {
            $this->dbConnection = new \PDO("mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db", $user, $password);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method for returning the connection.
     * 
     * @return PDO The database connection
     */
    public function getConnection()
    {
        return $this->dbConnection;
    }
}