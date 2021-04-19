<?php
/**
 * User.php
 *
 * User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class User {

    private $db = null;

    /**
     * 
     * Constructor of the User object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the users of the database in an associative array.
     * 
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll()
    {
        $statement = "
        SELECT id, email, firstname, lastname, phonenumber, address, api_token, code_role
        FROM user;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 
     * Method to return a user from the database in an associative array.
     * 
     * @param int $id The user identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT email, firstname, lastname, phonenumber, address, api_token, code_role
        FROM user
        WHERE id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return the role of a database user from its api token.
     * 
     * @param string $api_token The user api token 
     * @return int The user role code
     */
    public function getUser(string $api_token)
    {
        $statement = "
        SELECT id,code_role 
        FROM user
        WHERE api_token = :API_TOKEN
        LIMIT 1";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':API_TOKEN', $api_token, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}