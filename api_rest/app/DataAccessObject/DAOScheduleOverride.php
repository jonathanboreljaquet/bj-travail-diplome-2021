<?php
/**
 * DAOScheduleOverride.php
 *
 * Data access object of the schedule_override table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\ScheduleOverride;

class DAOScheduleOverride {

    private $db = null;

    /**
     * 
     * Constructor of the DAOScheduleOverride object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the schedule override of the database in an array of scheduleoverride objects.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted scheduleoverride
     * @param int $idEducator The educator identifier
     * @return ScheduleOverride[] A ScheduleOverride object array
     */
    public function findAll(bool $deleted, int $idEducator)
    {
        $statement ="
        SELECT id, date_schedule_override, id_educator
        FROM schedule_override
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR
        ORDER BY date_schedule_override";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $scheduleOverrideArray = array();
            
            foreach ($results as $result) {
                $scheduleOverride = new ScheduleOverride();
                $scheduleOverride->id = $result["id"];
                $scheduleOverride->date_schedule_override = $result["date_schedule_override"];
                $scheduleOverride->id_educator = $result["id_educator"];
                array_push($scheduleOverrideArray,$scheduleOverride);
            }

            return $scheduleOverrideArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return a schedule override from the database in a scheduleoverride model object.
     * 
     * @param int $id The scheduleoverride identifier 
     * @param int $idEducator The educator identifier
     * @return ScheduleOverride A ScheduleOverride model object containing all the result rows of the query 
     */
    public function find(int $id, int $idEducator)
    {
        $statement = "
        SELECT id, date_schedule_override, id_educator
        FROM schedule_override
        WHERE id = :ID_SCHEDULE_OVERRIDE
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->execute();
            
            $scheduleOverride = new ScheduleOverride();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $scheduleOverride->id = $result["id"];
                $scheduleOverride->date_schedule_override = $result["date_schedule_override"];
                $scheduleOverride->id_educator = $result["id_educator"];
            }
            else{
                $scheduleOverride = null;
            }

            return $scheduleOverride;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to insert a schedule override in the database.
     * 
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return int The number of rows affected by the insert
     */
    public function insert(ScheduleOverride $scheduleOverride)
    {
        $statement = "
        INSERT INTO schedule_override (date_schedule_override, id_educator,is_deleted) 
        VALUES(STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%Y-%m-%d\"), :ID_EDUCATOR, 0)";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $scheduleOverride->date_schedule_override, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $scheduleOverride->id_educator, \PDO::PARAM_INT);
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
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return int The number of rows affected by the update
     */
    public function update(ScheduleOverride $scheduleOverride)
    {
        $statement = "
        UPDATE schedule_override
        SET date_schedule_override = STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%Y-%m-%d\")
        WHERE id = :ID_SCHEDULE_OVERRIDE";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $scheduleOverride->date_schedule_override, \PDO::PARAM_STR);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $scheduleOverride->id, \PDO::PARAM_INT);  
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
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return int The number of rows affected by the delete
     */
    public function delete(ScheduleOverride $scheduleOverride)
    {
        $statement = "
        UPDATE schedule_override
        SET is_deleted = 1
        WHERE id = :ID_SCHEDULE_OVERRIDE";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $scheduleOverride->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to check if a date has not already been defined for the same educator in the database.
     * 
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return array The associative array containing all the result rows of the query 
     */
    public function findExistence(ScheduleOverride $scheduleOverride)
    {
        $statement = "
        SELECT *
        FROM schedule_override
        WHERE date_schedule_override = STR_TO_DATE(:DATE_SCHEDULE_OVERRIDE, \"%Y-%m-%d\")
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_SCHEDULE_OVERRIDE', $scheduleOverride->date_schedule_override, \PDO::PARAM_STR);
            $statement->bindParam(':ID_EDUCATOR', $scheduleOverride->id_educator, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}