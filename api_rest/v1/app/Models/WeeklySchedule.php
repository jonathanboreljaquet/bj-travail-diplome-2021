<?php
/**
 * WeeklySchedule.php
 *
 * WeeklySchedule model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class WeeklySchedule {

    private $db = null;

    /**
     * 
     * Constructor of the WeeklySchedule object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * 
     * Method to return all the weekly schedules of the database in an associative array.
     * 
     * @param bool $deleted Bool to define whether to search for existing or deleted weekly schedules
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll(bool $deleted, int $idEducator)
    {
        $statement = "
        SELECT id, date_valid_from, date_valid_to
        FROM weekly_schedule
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR;";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return a weekly schedule from the database in an associative array.
     * 
     * @param int $id The weekly schedule identifier 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id, int $idEducator)
    {
        $statement = "
        SELECT id, date_valid_from, date_valid_to
        FROM weekly_schedule
        WHERE id = :ID_WEEKLY_SCHEDULE
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to insert a weekly schedule in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @param int $idEducator The educator identifier
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input, int $idEducator)
    {
        $statement = "
        INSERT INTO weekly_schedule (date_valid_from, date_valid_to,id_educator, is_deleted) 
        VALUES(STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\"),STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\"),:ID_EDUCATOR, 0);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $input['date_valid_from'], \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $input['date_valid_to'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a weekly schedule in the database.
     * 
     * @param int $id The weekly schedule identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE weekly_schedule
        SET date_valid_from = STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\"), date_valid_to = STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\")
        WHERE id = :ID_WEEKLY_SCHEDULE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $input['date_valid_from'], \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $input['date_valid_to'], \PDO::PARAM_STR);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a weekly schedule in the database.
     * 
     * @param int $id The weekly schedule identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        UPDATE weekly_schedule
        SET is_deleted = 1
        WHERE id = :ID_WEEKLY_SCHEDULE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if a start date and an end date do not cause an overlapping problem.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findOverlap(array $input, int $idEducator)
    {
        $statement = "
        SELECT *
        FROM weekly_schedule
        WHERE is_deleted = 0
        AND id_educator = :ID_EDUCATOR
        AND (STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\") < date_valid_to OR date_valid_to IS NULL)
        AND STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\") > date_valid_from
        OR (STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\") IS NULL AND (STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\") < date_valid_to))";

        if (!isset($input['date_valid_to'])) {
            $input['date_valid_to'] = null;
        }
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $input['date_valid_from'], \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $input['date_valid_to'], \PDO::PARAM_STR);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);      
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if the database contains a weekly schedule not deleted without end date.
     * 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findActifPermanentSchedule(int $idEducator)
    {
        $statement = "
        SELECT *
        FROM weekly_schedule
        WHERE date_valid_to IS NULL
        AND is_deleted = 0
		AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);   
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}