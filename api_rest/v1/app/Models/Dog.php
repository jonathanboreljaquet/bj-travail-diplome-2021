<?php
/**
 * Dog.php
 *
 * Dog model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Dog {

    private $db = null;

    /**
     * 
     * Constructor of the Dog object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the dogs of the database in an associative array.
     * 
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll()
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_number, chip_id, user_id
        FROM dog;";

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
     * Method to return a dog from the database in an associative array.
     * 
     * @param int $id The dog identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_number, chip_id, user_id
        FROM dog
        WHERE id = :ID_DOG;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOG', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a dog from the database with the user id in an associative array.
     * 
     * @param int $userId The user identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function findWithUserId(int $userId)
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_number, chip_id, user_id
        FROM dog
        WHERE user_id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a dog in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input)
    {
        $statement = "
        INSERT INTO dog (name, breed, sex, picture_serial_number, chip_id, user_id) 
        VALUES(:NAME, :BREED, :SEX, :PICTURE_SERIAL_NUMBER, :CHIP_ID, :USER_ID);";

        
        if (!isset($input[''])) {
            $input['picture_serial_number'] = null;
        }

        if (!isset($input['chip_id'])) {
            $input['chip_id'] = null;
        }

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NAME', $input['name'], \PDO::PARAM_STR);
            $statement->bindParam(':BREED', $input['breed'], \PDO::PARAM_STR);    
            $statement->bindParam(':SEX', $input['sex'], \PDO::PARAM_STR);  
            $statement->bindParam(':PICTURE_SERIAL_NUMBER', $input['picture_serial_number'], \PDO::PARAM_STR);  
            $statement->bindParam(':CHIP_ID', $input['chip_id'], \PDO::PARAM_STR);
            $statement->bindParam(':USER_ID', $input['user_id'], \PDO::PARAM_STR);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a dog in the database.
     * 
     * @param int $id The dog identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE dog
        SET name = :NAME, 
        breed = :BREED,
        sex = :SEX, 
        picture_serial_number = :PICTURE_SERIAL_NUMBER, 
        chip_id = :CHIP_ID, 
        WHERE id = :ID_DOG;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NAME', $input['name'], \PDO::PARAM_STR);
            $statement->bindParam(':BREED', $input['breed'], \PDO::PARAM_STR);    
            $statement->bindParam(':SEX', $input['sex'], \PDO::PARAM_STR);  
            $statement->bindParam(':PICTURE_SERIAL_NUMBER', $input['picture_serial_number'], \PDO::PARAM_STR);  
            $statement->bindParam(':CHIP_ID', $input['chip_id'], \PDO::PARAM_STR);
            $statement->bindParam(':ID_DOG', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a dog in the database.
     * 
     * @param int $id The dog identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        DELETE FROM dog
        WHERE id = :ID_DOG;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOG', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}