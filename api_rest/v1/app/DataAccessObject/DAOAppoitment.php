<?php
/**
 * DAOAppoitment.php
 *
 * Data access object of the appoitment table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\Appoitment;

class DAOAppoitment {

    private $db = null;

    /**
     * 
     * Constructor of the DAOAppoitment object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the appoitments of the database in an array of appoitment objects.
     * 
     * @param int $userCustomerId The customer identifier 
     * @param int $userEducatorId The educator identifier 
     * @return Appoitment[] A Appoitment object array
     */
    public function findAll(int $userCustomerId = null, int $userEducatorId = null)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
		WHERE (user_id_customer = :ID_USER_CUSTOMER OR user_id_educator = :ID_USER_EDUCATOR)
		AND datetime_deletion IS NULL
		AND user_id_deletion IS NULL;";
 
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER_CUSTOMER', $userCustomerId, \PDO::PARAM_INT);
            $statement->bindParam(':ID_USER_EDUCATOR', $userEducatorId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $appoitmentArray = array();    

            foreach ($results as $result) {
                $appoitment = new Appoitment();
                $appoitment->id = $result["id"];
                $appoitment->datetime_appoitment = $result["datetime_appoitment"];
                $appoitment->duration_in_hour = $result["duration_in_hour"];
                $appoitment->note_text = $result["note_text"];
                $appoitment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appoitment->summary = $result["summary"];
                $appoitment->user_id_customer = $result["user_id_customer"];
                $appoitment->user_id_educator = $result["user_id_educator"];
                array_push($appoitmentArray,$appoitment);
            }

            return $appoitmentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return a appoitment from the database in an associative array.
     * 
     * @param int $id The appoitment identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_number, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
        WHERE id = :ID_APPOITMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_APPOITMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return all appoitment from the database with the user id in an array of appoitment objects.
     * 
     * @param int $userId The user identifier 
     * @return Appoitment[] A Appoitment object array
     */
    public function findByUserId(int $userId)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
        WHERE user_id_customer = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $appoitmentArray = array();    

            foreach ($results as $result) {
                $appoitment = new Appoitment();
                $appoitment->id = $result["id"];
                $appoitment->datetime_appoitment = $result["datetime_appoitment"];
                $appoitment->duration_in_hour = $result["duration_in_hour"];
                $appoitment->note_text = $result["note_text"];
                $appoitment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appoitment->summary = $result["summary"];
                $appoitment->user_id_customer = $result["user_id_customer"];
                $appoitment->user_id_educator = $result["user_id_educator"];
                array_push($appoitmentArray,$appoitment);
            }

            return $appoitmentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a appoitment in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input)
    {
        $statement = "
        INSERT INTO appoitment (datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_number, summary,
        user_id_customer,user_id_educator) 

        VALUES(:DATETIME_APPOITMENT, :DURATION_IN_HOUR, :NOTE_TEXT, 
        :NOTE_GRAPHICAL_SERIAL_NUMBER, :SUMMARY,
        :USER_ID_CUSTOMER,:USER_ID_EDUCATOR);";

        
        if (!isset($input['note_text'])) {
            $input['note_text'] = null;
        }

        if (!isset($input['note_graphical_serial_number'])) {
            $input['note_graphical_serial_number'] = null;
        }

        if (!isset($input['summary'])) {
            $input['summary'] = null;
        }

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATETIME_APPOITMENT', $input['datetime_appoitment'], \PDO::PARAM_STR);
            $statement->bindParam(':DURATION_IN_HOUR', $input['duration_in_hour'], \PDO::PARAM_STR);    
            $statement->bindParam(':NOTE_TEXT', $input['note_text'], \PDO::PARAM_STR);  
            $statement->bindParam(':NOTE_GRAPHICAL_SERIAL_NUMBER', $input['note_graphical_serial_number'], \PDO::PARAM_STR);  
            $statement->bindParam(':SUMMARY', $input['summary'], \PDO::PARAM_STR);
            $statement->bindParam(':USER_ID_CUSTOMER', $input['user_id_customer'], \PDO::PARAM_STR); 
            $statement->bindParam(':USER_ID_EDUCATOR', $input['user_id_educator'], \PDO::PARAM_STR);    
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a appoitment in the database.
     * 
     * @param int $id The appoitment identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE appoitment
        SET datetime_appoitment = :DATETIME_APPOITMENT, 
        duration_in_hour = :DURATION_IN_HOUR,
        note_text = :NOTE_TEXT, 
        note_graphical_serial_number = :NOTE_GRAPHICAL_SERIAL_NUMBER, 
        summary = :SUMMARY
        WHERE id = :ID_APPOITMENT;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATETIME_APPOITMENT', $input['datetime_appoitment'], \PDO::PARAM_STR);
            $statement->bindParam(':DURATION_IN_HOUR', $input['duration_in_hour'], \PDO::PARAM_STR);    
            $statement->bindParam(':NOTE_TEXT', $input['note_text'], \PDO::PARAM_STR);  
            $statement->bindParam(':NOTE_GRAPHICAL_SERIAL_NUMBER', $input['note_graphical_serial_number'], \PDO::PARAM_STR);  
            $statement->bindParam(':SUMMARY', $input['summary'], \PDO::PARAM_STR); 
            $statement->bindParam(':ID_APPOITMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a appoitment in the database.
     * 
     * @param int $id The appoitment identifier 
     * @param int $idUser The identifier of the user who is deleting the appointment
     * @return int The number of rows affected by the update
     */
    public function delete(int $id,$idUser)
    {
        $statement = "
        UPDATE appoitment
        SET datetime_deletion = NOW(), 
        user_id_deletion = :ID_USER
        WHERE id = :ID_APPOITMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_APPOITMENT', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_USER', $idUser, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}