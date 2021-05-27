<?php
/**
 * DAOTimeSlot.php
 *
 * Data access object of the time_slot table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\TimeSlot;

class DAOTimeSlot {

    private $db = null;

    /**
     * 
     * Constructor of the DAOTimeSlot object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the time slot for an educator of the database in an array of timeslot objects.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted timeslot
     * @param int $idEducator The educator identifier
     * @return TimeSlot[] A ScheduleOverride object array
     */
    public function findAll(bool $deleted,int $idEducator)
    {
        $statement ="
        SELECT id, code_day, time_start, time_end,id_weekly_schedule,id_schedule_override,id_educator
        FROM time_slot
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $timeSlotArray = array();
            
            foreach ($results as $result) {
                $timeSlot = new TimeSlot();
                $timeSlot->id = $result["id"];
                $timeSlot->code_day = $result["code_day"];
                $timeSlot->time_start = $result["time_start"];
                $timeSlot->time_end = $result["time_end"];
                $timeSlot->id_weekly_schedule = $result["id_weekly_schedule"];
                $timeSlot->id_schedule_override = $result["id_schedule_override"];
                $timeSlot->id_educator = $result["id_educator"];
                array_push($timeSlotArray,$timeSlot);
            }

            return $timeSlotArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return all the time slot of the database in an array of timeslot objects from his weekly schedule identifier.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted timeslot
     * @param int $idEducator The educator identifier
     * @param int $idWeeklySchedule The weekly schedule identifier
     * @return TimeSlot[] A ScheduleOverride object array
     */
    public function findAllByIdWeeklySchedule(bool $deleted,int $idEducator, int $idWeeklySchedule)
    {
        $statement ="
        SELECT id, code_day, time_start, time_end,id_weekly_schedule,id_schedule_override,id_educator
        FROM time_slot
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR
        AND id_weekly_schedule = :ID_WEEKLY_SCHEDULE
        ORDER BY code_day, time_start;";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $idWeeklySchedule, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $timeSlotArray = array();
            
            foreach ($results as $result) {
                $timeSlot = new TimeSlot();
                $timeSlot->id = $result["id"];
                $timeSlot->code_day = $result["code_day"];
                $timeSlot->time_start = $result["time_start"];
                $timeSlot->time_end = $result["time_end"];
                $timeSlot->id_weekly_schedule = $result["id_weekly_schedule"];
                $timeSlot->id_schedule_override = $result["id_schedule_override"];
                $timeSlot->id_educator = $result["id_educator"];
                array_push($timeSlotArray,$timeSlot);
            }

            return $timeSlotArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return all the time slot of the database in an array of timeslot objects from his schedule override identifier.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted timeslot
     * @param int $idEducator The educator identifier
     * @param int $idScheduleOverride The schedule override identifier
     * @return TimeSlot[] A ScheduleOverride object array
     */
    public function findAllByIdScheduleOverride(bool $deleted,int $idEducator, int $idScheduleOverride)
    {
        $statement ="
        SELECT id, code_day, time_start, time_end,id_weekly_schedule,id_schedule_override,id_educator
        FROM time_slot
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR
        AND id_schedule_override = :ID_SCHEDULE_OVERRIDE
        ORDER BY time_start;";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $deleted, \PDO::PARAM_BOOL);
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $idScheduleOverride, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $timeSlotArray = array();
            
            foreach ($results as $result) {
                $timeSlot = new TimeSlot();
                $timeSlot->id = $result["id"];
                $timeSlot->code_day = $result["code_day"];
                $timeSlot->time_start = $result["time_start"];
                $timeSlot->time_end = $result["time_end"];
                $timeSlot->id_weekly_schedule = $result["id_weekly_schedule"];
                $timeSlot->id_schedule_override = $result["id_schedule_override"];
                $timeSlot->id_educator = $result["id_educator"];
                array_push($timeSlotArray,$timeSlot);
            }

            return $timeSlotArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return a time slot from the database in a timeslot model object.
     * 
     * @param int $id The timeslot identifier 
     * @param int $idEducator The educator identifier
     * @return TimeSlot A TimeSlot model object containing all the result rows of the query 
     */
    public function find(int $id, int $idEducator)
    {
        $statement = "
        SELECT id, code_day, time_start, time_end,id_weekly_schedule,id_schedule_override,id_educator
        FROM time_slot
        WHERE id = :ID_TIMESLOT
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_TIMESLOT', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->execute();
            
            $timeSlot = new TimeSlot();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $timeSlot->id = $result["id"];
                $timeSlot->code_day = $result["code_day"];
                $timeSlot->time_start = $result["time_start"];
                $timeSlot->time_end = $result["time_end"];
                $timeSlot->id_weekly_schedule = $result["id_weekly_schedule"];
                $timeSlot->id_schedule_override = $result["id_schedule_override"];
                $timeSlot->id_educator = $result["id_educator"];
            }
            else{
                $timeSlot = null;
            }

            return $timeSlot;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to insert a time slot in the database.
     * 
     * @param TimeSlot $timeSlot The timeslot model object
     * @return int The number of rows affected by the insert
     */
    public function insert(TimeSlot $timeSlot)
    {
        $statement = "
        INSERT INTO time_slot (code_day, time_start, time_end, is_deleted, id_weekly_schedule, id_schedule_override, id_educator) 
        VALUES(:CODE_DAY, :TIME_START, :TIME_END, 0, :ID_WEEKLY_SCHEDULE, :ID_SCHEDULE_OVERRIDE,:ID_EDUCATOR);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $timeSlot->code_day, \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $timeSlot->time_start, \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $timeSlot->time_end, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $timeSlot->id_weekly_schedule, \PDO::PARAM_INT);  
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $timeSlot->id_schedule_override, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $timeSlot->id_educator, \PDO::PARAM_INT);   
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
     * @param TimeSlot $timeSlot The timeslot model object
     * @return int The number of rows affected by the update
     */
    public function update(TimeSlot $timeSlot)
    {
        $statement = "
        UPDATE time_slot
        SET code_day = :CODE_DAY, time_start = :TIME_START, time_end = :TIME_END, id_weekly_schedule = :ID_WEEKLY_SCHEDULE, id_schedule_override = :ID_SCHEDULE_OVERRIDE
        WHERE id = :ID_TIMESLOT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $timeSlot->code_day, \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $timeSlot->time_start, \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $timeSlot->time_end, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $timeSlot->id_weekly_schedule, \PDO::PARAM_INT);  
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $timeSlot->id_schedule_override, \PDO::PARAM_INT);
            $statement->bindParam(':ID_TIMESLOT', $timeSlot->id, \PDO::PARAM_INT);
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
     * @param TimeSlot $timeSlot The timeslot model object
     * @return int The number of rows affected by the delete
     */
    public function delete(TimeSlot $timeSlot)
    {
        $statement = "
        UPDATE time_slot
        SET is_deleted = 1
        WHERE id = :ID_TIMESLOT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_TIMESLOT', $timeSlot->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if a time slot causes an overlapping problem in a weekly schedule.
     * 
     * @param TimeSlot $timeSlot The timeslot model object
     * @return array The associative array containing all the result rows of the query 
     */
    public function findOverlapInWeeklySchedule(TimeSlot $timeSlot)
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
            $statement->bindParam(':CODE_DAY', $timeSlot->code_day, \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $timeSlot->time_start, \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $timeSlot->time_end, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_WEEKLY_SCHEDULE', $timeSlot->id_weekly_schedule, \PDO::PARAM_INT); 
            $statement->bindParam(':ID_EDUCATOR', $timeSlot->id_educator, \PDO::PARAM_INT);   
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to check if a time slot causes an overlapping problem in a schedule override.
     * 
     * @param TimeSlot $timeSlot The timeslot model object
     * @return array The associative array containing all the result rows of the query 
     */
    public function findOverlapInScheduleOverride(TimeSlot $timeSlot)
    {
        $statement = "
        SELECT ts.code_day, ts.time_start, ts.time_end
        FROM time_slot AS ts
        LEFT JOIN schedule_override AS so
        ON ts.id_schedule_override = so.id   
        WHERE ts.id_schedule_override = :ID_SCHEDULE_OVERRIDE
        AND ts.is_deleted = 0
        AND so.is_deleted = 0
        AND :TIME_START < time_end
        AND :TIME_END > time_start
        AND code_day = :CODE_DAY
        AND ts.id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':CODE_DAY', $timeSlot->code_day, \PDO::PARAM_INT);
            $statement->bindParam(':TIME_START', $timeSlot->time_start, \PDO::PARAM_STR);    
            $statement->bindParam(':TIME_END', $timeSlot->time_end, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_SCHEDULE_OVERRIDE', $timeSlot->id_schedule_override, \PDO::PARAM_INT); 
            $statement->bindParam(':ID_EDUCATOR', $timeSlot->id_educator, \PDO::PARAM_INT);   
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
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
    private function generateViews()
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
    public function findPlanningForEducator(int $idEducator)
    {
        $this->generateViews();
        $statement = "   
        SELECT IF(dates.date IS NOT NULL, dates.date, so.date_schedule_override) AS date,time_start,time_end 

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
        WHERE ab.id_educator = :ID_EDUCATOR 
        AND ab.is_deleted = 0
        AND IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override) BETWEEN ab.date_absence_from AND ab.date_absence_to LIMIT 1) = 0

        AND (SELECT COUNT(*)
        FROM appoitment AS ap
        WHERE ap.user_id_educator = :ID_EDUCATOR
        AND datetime_deletion IS NULL
        AND user_id_deletion IS NULL
        AND DATE(ap.datetime_appoitment) = IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override)
        AND TIME(ap.datetime_appoitment) = ts.time_start
        AND TIME(ADDTIME(ap.datetime_appoitment,SEC_TO_TIME(3600* ap.duration_in_hour))) = ts.time_end LIMIT 1) = 0
        AND IF(so.date_schedule_override IS NULL,dates.date > NOW() ,so.date_schedule_override >NOW())
        
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

    /**
     * 
     * Method to check if appointment data is valid in an educator's planning. 
     * 
     * @param string $date The date of the appoitment
     * @param string $time_start The start time of the appoitment
     * @param string $time_end The end time of the appoitment
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAppoitmentSlotsForEducator(string $date,string $time_start, string $time_end,int $idEducator)
    {
        $this->generateViews();
        $statement = "        
        SELECT IF(dates.date IS NOT NULL, dates.date, so.date_schedule_override) AS date,time_start,time_end 

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
        WHERE ab.id_educator = :ID_EDUCATOR
        AND IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override) BETWEEN ab.date_absence_from AND ab.date_absence_to LIMIT 1) = 0

        AND (SELECT COUNT(*)
        FROM appoitment AS ap
        WHERE ap.user_id_educator = :ID_EDUCATOR
        AND ap.user_id_deletion IS NULL
        AND ap.datetime_deletion IS NULL
        AND DATE(ap.datetime_appoitment) = IF(so.date_schedule_override IS NULL,dates.date,so.date_schedule_override)
        AND TIME(ap.datetime_appoitment) = ts.time_start
        AND TIME(ADDTIME(ap.datetime_appoitment,SEC_TO_TIME(3600* ap.duration_in_hour))) = ts.time_end LIMIT 1) = 0
        AND IF(so.date_schedule_override IS NULL,dates.date > NOW() ,so.date_schedule_override >NOW())
        
        HAVING DATE = :DATE
        AND time_start = :TIME_START
        AND time_end = :TIME_END
 
        ORDER BY date,time_start;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE', $date, \PDO::PARAM_STR);  
            $statement->bindParam(':TIME_START', $time_start, \PDO::PARAM_STR);  
            $statement->bindParam(':TIME_END', $time_end, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);  
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}