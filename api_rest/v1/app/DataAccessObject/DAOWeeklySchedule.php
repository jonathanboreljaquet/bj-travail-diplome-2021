<?php
/**
 * DAOWeeklySchedule.php
 *
 * Data access object of the weekly_schedule table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Controllers\WeeklyScheduleController;
use App\Models\WeeklySchedule;

class DAOWeeklySchedule {

    private $db = null;

    /**
     * 
     * Constructor of the DAOWeeklySchedule object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the weekly schedule of the database in an array of weeklyschedule objects.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted weeklyschedules
     * @param int $idEducator The educator identifier
     * @return WeeklySchedule[] A WeeklySchedule object array
     */
    public function findAll(bool $deleted, int $idEducator)
    {
        $statement = "
        SELECT id, date_valid_from, date_valid_to, id_educator
        FROM weekly_schedule
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR;";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $weeklyScheduleArray = array();
            
            foreach ($results as $result) {
                $weeklySchedule = new WeeklySchedule();
                $weeklySchedule->id = $result["id"];
                $weeklySchedule->date_valid_from = $result["date_valid_from"];
                $weeklySchedule->date_valid_to = $result["date_valid_to"];
                $weeklySchedule->id_educator = $result["id_educator"];
                array_push($weeklyScheduleArray,$weeklySchedule);
            }

            return $weeklyScheduleArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return an weekly schedule from the database in a weeklyschedule model object.
     * 
     * @param int $id The weeklyschedule identifier 
     * @param int $idEducator The educator identifier
     * @return WeeklySchedule A WeeklySchedule model object containing all the result rows of the query 
     */
    public function find(int $id, int $idEducator)
    {
        $statement = "
        SELECT id, date_valid_from, date_valid_to, id_educator
        FROM weekly_schedule
        WHERE id = :ID_WEEKLY_SCHEDULE
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->execute();
            
            $weeklySchedule = new WeeklySchedule();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $weeklySchedule->id = $result["id"];
                $weeklySchedule->date_valid_from = $result["date_valid_from"];
                $weeklySchedule->date_valid_to = $result["date_valid_to"];
                $weeklySchedule->id_educator = $result["id_educator"];
            }
            else{
                $weeklySchedule = null;
            }

            return $weeklySchedule;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a weekly schedule in the database.
     * 
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return int The number of rows affected by the insert
     */
    public function insert(WeeklySchedule $weeklySchedule)
    {
        $statement = "
        INSERT INTO weekly_schedule (date_valid_from, date_valid_to,id_educator, is_deleted) 
        VALUES(STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\"),STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\"),:ID_EDUCATOR, 0);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $weeklySchedule->date_valid_from, \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $weeklySchedule->date_valid_to, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $weeklySchedule->id_educator, \PDO::PARAM_INT);  
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
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return int The number of rows affected by the update
     */
    public function update(WeeklySchedule $weeklySchedule)
    {
        $statement = "
        UPDATE weekly_schedule
        SET date_valid_from = STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\"), date_valid_to = STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\")
        WHERE id = :ID_WEEKLY_SCHEDULE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $weeklySchedule->date_valid_from, \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $weeklySchedule->date_valid_to, \PDO::PARAM_STR);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $weeklySchedule->id, \PDO::PARAM_INT);  
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
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return int The number of rows affected by the delete
     */
    public function delete(WeeklySchedule $weeklySchedule)
    {
        $statement = "
        UPDATE weekly_schedule
        SET is_deleted = 1
        WHERE id = :ID_WEEKLY_SCHEDULE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $weeklySchedule->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if a start date and an end date of a weekly schedule do not cause an overlapping problem.
     * 
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return array The associative array containing all the result rows of the query 
     */
    public function findOverlap(WeeklySchedule $weeklySchedule)
    {
        $statement = "
        SELECT *
        FROM weekly_schedule
        WHERE is_deleted = 0
        AND id_educator = :ID_EDUCATOR
        AND id != :ID_HIMSELF
        AND (STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\") < date_valid_to OR date_valid_to IS NULL)
        AND STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\") > date_valid_from
        OR (STR_TO_DATE(:DATE_VALID_TO, \"%Y-%m-%d\") IS NULL AND (STR_TO_DATE(:DATE_VALID_FROM, \"%Y-%m-%d\") < date_valid_to))";

        if (!isset($input['date_valid_to'])) {
            $input['date_valid_to'] = null;
        }
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_VALID_FROM', $weeklySchedule->date_valid_from, \PDO::PARAM_STR);
            $statement->bindParam(':DATE_VALID_TO', $weeklySchedule->date_valid_to, \PDO::PARAM_STR);
            $statement->bindParam(':ID_EDUCATOR', $weeklySchedule->id_educator, \PDO::PARAM_INT);      
            $statement->bindParam(':ID_HIMSELF', $weeklySchedule->id, \PDO::PARAM_INT);      
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
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return array The associative array containing all the result rows of the query 
     */
    public function findActifPermanentSchedule(WeeklySchedule $weeklySchedule)
    {
        $statement = "
        SELECT *
        FROM weekly_schedule
        WHERE date_valid_to IS NULL
        AND is_deleted = 0
        AND id != :ID_HIMSELF
		AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $weeklySchedule->id_educator, \PDO::PARAM_INT);   
            $statement->bindParam(':ID_HIMSELF', $weeklySchedule->id, \PDO::PARAM_INT);   
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}