<?php
/**
 * ScheduleOverride.php
 *
 * ScheduleOverride model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class ScheduleOverride {

    private $db = null;

    /**
     * 
     * Constructor of the ScheduleOverride object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * 
     * Method to return all the schedule overrides of the database in an associative array.
     * 
     * @param bool $isDeleted Bool to define whether to search for existing or deleted schedule overrides
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll(bool $isDeleted)
    {
        $statement ="
        SELECT id, date_schedule_override
        FROM schedule_override
        WHERE is_deleted=".(int)$isDeleted;
        
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
     * Method to return a schedule override from the database in an associative array.
     * 
     * @param int $id The schedule override identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, date_schedule_override
        FROM schedule_override
        WHERE id = :ID_SCHEDULE_OVERRIDE
        AND is_deleted = 0;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to insert a schedule override in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input)
    {
        $statement = "
        INSERT INTO schedule_override (date_schedule_override, is_deleted) 
        VALUES(STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%d-%m-%Y\"), 0);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $input['date_schedule_override'], \PDO::PARAM_STR);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a schedule override in the database.
     * 
     * @param int $id The schedule override identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE schedule_override
        SET date_schedule_override = STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%d-%m-%Y\")
        WHERE id = :ID_SCHEDULE_OVERRIDE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $input['date_schedule_override'], \PDO::PARAM_STR);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a schedule override in the database.
     * 
     * @param int $id The schedule override identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        UPDATE schedule_override
        SET is_deleted = 1
        WHERE id = :ID_SCHEDULE_OVERRIDE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to check if a date has not already been defined in the database.
     * 
     * @param string $date The date to check
     * @return array The associative array containing all the result rows of the query 
     */
    public function findExistence(string $date)
    {
        $statement = "
        SELECT *
        FROM schedule_override
        WHERE date_schedule_override = STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%d-%m-%Y\")
        AND is_deleted = 0;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $date, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}