<?php
/**
 * TimeSlot.php
 *
 * TimeSlot model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class TimeSlot {

    private $db = null;

    /**
     * 
     * Constructor of the TimeSlot object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * 
     * Method to return all the time slots of the database in an associative array.
     * 
     * @param bool $deleted Bool to define whether to search for existing or deleted time slots
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll(bool $deleted,int $idEducator)
    {
        $statement ="
        SELECT ts.id, ts.code_day, ts.time_start, ts.time_end
        FROM time_slot AS ts
        LEFT JOIN weekly_schedule AS ws
        ON ts.id_weekly_schedule = ws.id 
        LEFT JOIN schedule_override AS so
        ON ts.id_schedule_override = so.id 
        WHERE ts.is_deleted = :DELETED
        AND (so.is_deleted = 0 OR ws.is_deleted = 0)
        AND ts.id_educator = :ID_EDUCATOR
        GROUP BY ts.id;";
        
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
     * Method to return a time slot from the database in an associative array.
     * 
     * @param int $id The time slot identifier 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id, int $idEducator)
    {
        $statement = "
        SELECT id, code_day, time_start, time_end
        FROM time_slot
        WHERE id = :ID_TIMESLOT
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_TIMESLOT', $id, \PDO::PARAM_INT);
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
     * Method to insert a time slot in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @param int $idEducator The educator identifier
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input, int $idEducator)
    {
        $statement = "
        INSERT INTO time_slot (code_day, time_start, time_end, is_deleted, id_weekly_schedule, id_schedule_override, id_educator) 
        VALUES(:CODE_DAY, :TIME_START, :TIME_END, 0, :ID_WEEKLY_SCHEDULE, :ID_SCHEDULE_OVERRIDE,:ID_EDUCATOR);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $input['code_day'], \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $input['time_start'], \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $input['time_end'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $input['id_weekly_schedule'], \PDO::PARAM_INT);  
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $input['id_schedule_override'], \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);   
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a time slot in the database.
     * 
     * @param int $id The time slot identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE time_slot
        SET code_day = :CODE_DAY, time_start = :TIME_START, time_end = :TIME_END, id_weekly_schedule = :ID_WEEKLY_SCHEDULE, id_schedule_override = :ID_SCHEDULE_OVERRIDE
        WHERE id = :ID_TIMESLOT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $input['code_day'], \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $input['time_start'], \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $input['time_end'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $input['id_weekly_schedule'], \PDO::PARAM_INT);  
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $input['id_schedule_override'], \PDO::PARAM_INT); 
            $statement->bindParam(':ID_TIMESLOT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a time slot in the database.
     * 
     * @param int $id The time slot identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        UPDATE time_slot
        SET is_deleted = 1
        WHERE id = :ID_TIMESLOT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_TIMESLOT', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if a time slot causes an overlapping problem in a weekly calendar.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findOverlapInWeeklySchedule(array $input,int $idEducator)
    {
        $statement = "
        SELECT ts.code_day, ts.time_start, ts.time_end
        FROM time_slot AS ts
        LEFT JOIN weekly_schedule AS ws
        ON ts.id_weekly_schedule = ws.id
        WHERE ts.id_weekly_schedule = :ID_WEEKLY_SCHEDULE
        AND ts.is_deleted = 0
        AND ws.is_deleted = 0
        AND :TIME_START < time_end
        AND :TIME_END > time_start
        AND code_day = :CODE_DAY
        AND ts.id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $input['code_day'], \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $input['time_start'], \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $input['time_end'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $input['id_weekly_schedule'], \PDO::PARAM_INT); 
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);   
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method for generating virtual views of the application.
     * 1. digits : Table containing the values from 0 to 9
     * 2. numbers : Table containing numbers from 0 to 999
     * 3. dates : Table containing dates from (today - 999 days) to (today + 1 year)
     * 
     * @return void  
     */
    private function generateViews() : void
    {
        $statement = "
        CREATE OR REPLACE VIEW digits AS
        SELECT 0 AS digit UNION ALL
        SELECT 1 UNION ALL
        SELECT 2 UNION ALL
        SELECT 3 UNION ALL
        SELECT 4 UNION ALL
        SELECT 5 UNION ALL
        SELECT 6 UNION ALL
        SELECT 7 UNION ALL
        SELECT 8 UNION ALL
        SELECT 9;
  
        CREATE OR REPLACE VIEW numbers AS
        SELECT ones.digit + tens.digit * 10 + hundreds.digit * 100 AS number
        FROM digits as ones, digits as tens, digits as hundreds;

        CREATE OR REPLACE VIEW dates AS
        SELECT SUBDATE(ADDDATE(CURRENT_DATE(),365), number) AS date
        FROM numbers;";

        $this->db->exec($statement);
    }

    /**
     * 
     * Method to retrieve the available valid time slots with their corresponding dates.
     * 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findPlanningTimeSlots($idEducator)
    {
        $this->generateViews();
        $statement = "
        SELECT time_start,time_end, IF(dates.date IS NOT NULL, dates.date, so.date_schedule_override) AS date

        FROM time_slot AS ts
        LEFT JOIN weekly_schedule AS ws
        ON ws.id = ts.id_weekly_schedule

        LEFT JOIN schedule_override AS so
        ON so.id = ts.id_schedule_override

        LEFT JOIN dates
        ON DAYOFWEEK(dates.date) = ts.code_day 
        AND dates.date BETWEEN ws.date_valid_from 
        AND IF(ws.date_valid_to IS NULL, DATE_ADD(NOW(), INTERVAL 365 DAY), ws.date_valid_to) 

        WHERE ts.is_deleted = 0 AND (so.is_deleted = 0 OR ws.is_deleted = 0)
        AND ts.id_educator = :ID_EDUCATOR
        AND (SELECT COUNT(*) 
        FROM absence AS ab
        WHERE IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override) BETWEEN ab.date_absence_from AND ab.date_absence_to LIMIT 1) = 0

        ORDER BY date,time_start;";

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