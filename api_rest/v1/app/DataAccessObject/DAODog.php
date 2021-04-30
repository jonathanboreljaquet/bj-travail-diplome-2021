<?php
/**
 * DAODog.php
 *
 * Data access object of the dog table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\Dog;


class DAODog {

    private \PDO $db;

    /**
     * 
     * Constructor of the DAODog object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all dogs from the database in an array of dog objects.
     * 
     * @return Dog[] A Dog object array
     */
    public function findAll()
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_id, chip_id, user_id
        FROM dog;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $dogArray = array();
            
            foreach ($results as $result) {
                $dog = new Dog();
                $dog->id = $result["id"];
                $dog->name = $result["name"];
                $dog->breed = $result["breed"];
                $dog->sex = $result["sex"];
                $dog->picture_serial_id = $result["picture_serial_id"];
                $dog->chip_id = $result["chip_id"];
                $dog->user_id = $result["user_id"];
                array_push($dogArray,$dog);
            }

            return $dogArray;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 
     * Method to return a dog from the database in a dog model object.
     * 
     * @param int $id The dog identifier 
     * @return Dog A Dog model object containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_id, chip_id, user_id
        FROM dog
        WHERE id = :ID_DOG;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOG', $id, \PDO::PARAM_INT);
            $statement->execute();
            
            $dog = new Dog();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $dog->id = $result["id"];
                $dog->name = $result["name"];
                $dog->breed = $result["breed"];
                $dog->sex = $result["sex"];
                $dog->picture_serial_id = $result["picture_serial_id"];
                $dog->chip_id = $result["chip_id"];
                $dog->user_id = $result["user_id"];
            }
            else{
                $dog = null;
            }

            return $dog;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return all dogs from the database with the user id in an array of dog objects.
     * 
     * @param int $userId The user identifier 
     * @return Dog[] A Dog object array
     */
    public function findByUserId(int $userId)
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_id, chip_id, user_id
        FROM dog
        WHERE user_id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $dogArray = array();
            
            foreach ($results as $result) {
                $dog = new Dog();
                $dog->id = $result["id"];
                $dog->name = $result["name"];
                $dog->breed = $result["breed"];
                $dog->sex = $result["sex"];
                $dog->picture_serial_id = $result["picture_serial_id"];
                $dog->chip_id = $result["chip_id"];
                $dog->user_id = $result["user_id"];
                array_push($dogArray,$dog);
            }

            return $dogArray;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a dog from the database from his picture serial id.
     * 
     * @param string $serial_id The dog picture serial id  
     * @return Dog A Dog model object containing all the result rows of the query 
     */
    public function findBySerialId(string $serial_id)
    {
        $statement = "
        SELECT id, name, breed, sex, picture_serial_id, chip_id, user_id 
        FROM dog
        WHERE picture_serial_id = :SERIAL_ID;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':SERIAL_ID', $serial_id, \PDO::PARAM_STR);
            $statement->execute();

            $dog = new Dog();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $dog->id = $result["id"];
                $dog->name = $result["name"];
                $dog->breed = $result["breed"];
                $dog->sex = $result["sex"];
                $dog->picture_serial_id = $result["picture_serial_id"];
                $dog->chip_id = $result["chip_id"];
                $dog->user_id = $result["user_id"];
            }
            else{
                $dog = null;
            }

            return $dog;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a dog in the database.
     * 
     * @param Dog $dog The dog model object
     * @return int The number of rows affected by the insert
     */
    public function insert(Dog $dog)
    {
        $statement = "
        INSERT INTO dog (name, breed, sex, picture_serial_id, chip_id, user_id) 
        VALUES(:NAME, :BREED, :SEX, :PICTURE_SERIAL_ID, :CHIP_ID, :USER_ID);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NAME', $dog->name, \PDO::PARAM_STR);
            $statement->bindParam(':BREED', $dog->breed, \PDO::PARAM_STR);    
            $statement->bindParam(':SEX', $dog->sex, \PDO::PARAM_STR);  
            $statement->bindParam(':PICTURE_SERIAL_ID', $dog->picture_serial_id, \PDO::PARAM_STR);  
            $statement->bindParam(':CHIP_ID', $dog->chip_id, \PDO::PARAM_STR);
            $statement->bindParam(':USER_ID', $dog->user_id, \PDO::PARAM_STR);  
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
     * @param Dog $dog The dog model object
     * @return int The number of rows affected by the update
     */
    public function update(Dog $dog)
    {
        $statement = "
        UPDATE dog
        SET name = :NAME, 
        breed = :BREED,
        sex = :SEX, 
        picture_serial_id = :PICTURE_SERIAL_ID, 
        chip_id = :CHIP_ID
        WHERE id = :ID_DOG;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NAME', $dog->name, \PDO::PARAM_STR);
            $statement->bindParam(':BREED', $dog->breed, \PDO::PARAM_STR);    
            $statement->bindParam(':SEX', $dog->sex, \PDO::PARAM_STR);  
            $statement->bindParam(':PICTURE_SERIAL_ID', $dog->picture_serial_id, \PDO::PARAM_STR);  
            $statement->bindParam(':CHIP_ID', $dog->chip_id, \PDO::PARAM_STR);
            $statement->bindParam(':ID_DOG', $dog->id, \PDO::PARAM_INT);
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
     * @param Dog $dog The dog model object
     * @return int The number of rows affected by the delete
     */
    public function delete(Dog $dog)
    {
        $statement = "
        DELETE FROM dog
        WHERE id = :ID_DOG;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOG', $dog->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}