<?php
/**
 * DAOUser.php
 *
 * Data access object of the user table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\User;

class DAOUser {

    private \PDO $db;

    /**
     * 
     * Constructor of the DAOUser object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all users with the selected role from the database in an array of user objects.
     * 
     * @return User[] A User object array
     */
    public function findAll(int $code_role)
    {
        $statement = "
        SELECT id, email, firstname, lastname, phonenumber, address
        FROM user
        WHERE code_role = :CODE_ROLE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_ROLE', $code_role, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $userArray = array();
            
            foreach ($results as $result) {
                $user = new User();
                $user->id = $result["id"];
                $user->email = $result["email"];
                $user->firstname = $result["firstname"];
                $user->lastname = $result["lastname"];
                $user->phonenumber = $result["phonenumber"];
                $user->address = $result["address"];
                array_push($userArray,$user);
            }
            return $userArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 
     * Method to return a user from the database in a user model object.
     * 
     * @param int $id The user identifier 
     * @return User A User model object containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id,email, firstname, lastname, phonenumber, address,api_token, code_role, password_hash
        FROM user
        WHERE id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $id, \PDO::PARAM_INT);
            $statement->execute();
            $user = new User();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $user->id = $result["id"];
                $user->email = $result["email"];
                $user->firstname = $result["firstname"];
                $user->lastname = $result["lastname"];
                $user->phonenumber = $result["phonenumber"];
                $user->address = $result["address"];
                $user->api_token = $result["api_token"];
                $user->code_role = $result["code_role"];
                $user->password_hash = $result["password_hash"];
            }
            else{
                $user = null; 
            }

            return $user;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a user in the database.
     * 
     * @param User $user The user model object
     * @return int The number of rows affected by the insert
     */
    public function insert(User $user)
    {
        $statement = "
        INSERT INTO user (email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash) 
        VALUES(:EMAIL, :FIRSTNAME, :LASTNAME, :PHONENUMBER, :ADDRESS, :API_TOKEN, :CODE_ROLE, :PASSWORD_HASH);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':EMAIL', $user->email, \PDO::PARAM_STR);
            $statement->bindParam(':FIRSTNAME', $user->firstname, \PDO::PARAM_STR);    
            $statement->bindParam(':LASTNAME', $user->lastname, \PDO::PARAM_STR);  
            $statement->bindParam(':PHONENUMBER', $user->phonenumber, \PDO::PARAM_STR);  
            $statement->bindParam(':ADDRESS', $user->address, \PDO::PARAM_STR);
            $statement->bindParam(':API_TOKEN', $user->api_token, \PDO::PARAM_STR);  
            $statement->bindParam(':CODE_ROLE', $user->code_role, \PDO::PARAM_INT);  
            $statement->bindParam(':PASSWORD_HASH', $user->password_hash, \PDO::PARAM_STR); 
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
     * @param User $user The user model object
     * @return int The number of rows affected by the update
     */
    public function update(User $user)
    {
        $statement = "
        UPDATE user
        SET email = :EMAIL, 
        firstname = :FIRSTNAME, 
        lastname = :LASTNAME, 
        phonenumber = :PHONENUMBER, 
        address = :ADDRESS,
        api_token = :API_TOKEN,
        code_role = :CODE_ROLE,
        password_hash = :PASSWORD_HASH
        WHERE id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':EMAIL', $user->email, \PDO::PARAM_STR);
            $statement->bindParam(':FIRSTNAME', $user->firstname, \PDO::PARAM_STR);    
            $statement->bindParam(':LASTNAME', $user->lastname, \PDO::PARAM_STR);  
            $statement->bindParam(':PHONENUMBER', $user->phonenumber, \PDO::PARAM_STR);  
            $statement->bindParam(':ADDRESS', $user->address, \PDO::PARAM_STR); 
            $statement->bindParam(':API_TOKEN', $user->api_token, \PDO::PARAM_STR); 
            $statement->bindParam(':CODE_ROLE', $user->code_role, \PDO::PARAM_STR); 
            $statement->bindParam(':PASSWORD_HASH', $user->password_hash, \PDO::PARAM_STR); 
            $statement->bindParam(':ID_USER', $user->id, \PDO::PARAM_INT);
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
     * @param User $user The user model object
     * @return int The number of rows affected by the delete
     */
    public function delete(User $user)
    {
        $statement = "
        DELETE FROM user
        WHERE id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $user->id, \PDO::PARAM_INT);  
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
     * @return User A User model object containing all the result rows of the query 
     */
    public function findUserWithApiToken(string $api_token)
    {
        $statement = "
        SELECT id, email, firstname, lastname, phonenumber, address, api_token, code_role,password_hash
        FROM user
        WHERE api_token = :API_TOKEN
        LIMIT 1";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':API_TOKEN', $api_token, \PDO::PARAM_STR);
            $statement->execute();
            $user = new User();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $user->id = $result["id"];
                $user->email = $result["email"];
                $user->firstname = $result["firstname"];
                $user->lastname = $result["lastname"];
                $user->phonenumber = $result["phonenumber"];
                $user->address = $result["address"];
                $user->api_token = $result["api_token"];
                $user->code_role = $result["code_role"];
            }
            else{
                $user = null;
            }

            return $user;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a user from the database user from his email.
     * 
     * @param string $email The user email 
     * @return User A User model object containing all the result rows of the query 
     */
    public function findUserWithEmail(string $email)
    {
        $statement = "
        SELECT id, email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash
        FROM user
        WHERE email = :EMAIL
        LIMIT 1";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':EMAIL', $email, \PDO::PARAM_STR);
            $statement->execute();

            $user = new User();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $user->id = $result["id"];
                $user->email = $result["email"];
                $user->firstname = $result["firstname"];
                $user->lastname = $result["lastname"];
                $user->phonenumber = $result["phonenumber"];
                $user->address = $result["address"];
                $user->api_token = $result["api_token"];
                $user->code_role = $result["code_role"];
                $user->password_hash = $result["password_hash"];
            }
            else {
                $user = null;
            }

            return $user;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}