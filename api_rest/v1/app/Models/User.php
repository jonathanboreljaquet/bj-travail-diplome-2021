<?php
/**
 * User.php
 *
 * User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

use App\Controllers\HelperController;
use App\System\Constants;

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
     * Method to insert a user in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input)
    {
        $statement = "
        INSERT INTO user (email, firstname, lastname, phonenumber, address, api_token, code_role) 
        VALUES(:EMAIL, :FIRSTNAME, :LASTNAME, :PHONENUMBER, :ADDRESS, :API_TOKEN, :CODE_ROLE);";

        $api_token = HelperController::generateApiToken();
        $code_role = Constants::USER_CODE_ROLE;

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':EMAIL', $input['email'], \PDO::PARAM_STR);
            $statement->bindParam(':FIRSTNAME', $input['firstname'], \PDO::PARAM_STR);    
            $statement->bindParam(':LASTNAME', $input['lastname'], \PDO::PARAM_STR);  
            $statement->bindParam(':PHONENUMBER', $input['phonenumber'], \PDO::PARAM_STR);  
            $statement->bindParam(':ADDRESS', $input['address'], \PDO::PARAM_STR);
            $statement->bindParam(':API_TOKEN', $api_token, \PDO::PARAM_STR);  
            $statement->bindParam(':CODE_ROLE', $code_role, \PDO::PARAM_STR);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a user in the database.
     * 
     * @param int $id The user identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE time_slot
        SET email = :EMAIL, 
        fisrtname = :FIRSTNAME, 
        lastname = :LASTNAME, 
        phonenumber = :PHONENUMBER, 
        address = :ADDRESS, 
        password_hash = :PASSWORD_HASH
        WHERE id = :ID_USER;";

        if (!isset($input['password_hash'])) {
            $input['password_hash'] = null;
        }
        else{
            $input['password_hash'] = password_hash($input['password_hash'],PASSWORD_DEFAULT);
        }
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':EMAIL', $input['email'], \PDO::PARAM_STR);
            $statement->bindParam(':FIRSTNAME', $input['firstname'], \PDO::PARAM_STR);    
            $statement->bindParam(':LASTNAME', $input['lastname'], \PDO::PARAM_STR);  
            $statement->bindParam(':PHONENUMBER', $input['phonenumber'], \PDO::PARAM_STR);  
            $statement->bindParam(':ADDRESS', $input['address'], \PDO::PARAM_STR); 
            $statement->bindParam(':PASSWORD_HASH', $input["password_hash"], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_USER', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a user in the database.
     * 
     * @param int $id The user identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        DELETE FROM user
        WHERE id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a user from the database user from his api token.
     * 
     * @param string $api_token The user api token 
     * @return array The associative array containing all the result rows of the query 
     */
    public function getUser(string $api_token)
    {
        $statement = "
        SELECT id, email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash
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