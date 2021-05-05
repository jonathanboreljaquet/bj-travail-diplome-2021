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
     * Method to return a appoitment slot from the database in a appoitment model object.
     * 
     * @param int $id The appoitment identifier 
     * @param int $idEducator The educator identifier
     * @return Appoitment A Appoitment model object containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
        WHERE id = :ID_APPOITMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_APPOITMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            $appoitment = new Appoitment();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $appoitment->id = $result["id"];
                $appoitment->datetime_appoitment = $result["datetime_appoitment"];
                $appoitment->duration_in_hour = $result["duration_in_hour"];
                $appoitment->note_text = $result["note_text"];
                $appoitment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appoitment->summary = $result["summary"];
                $appoitment->user_id_customer = $result["user_id_customer"];
                $appoitment->user_id_educator = $result["user_id_educator"];
            }
            else{
                $appoitment = null;
            }

            return $appoitment;
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
    public function findByUserIdForCustomer(int $userId)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
        WHERE user_id_customer = :ID_USER
        AND datetime_deletion IS NULL
        AND user_id_deletion IS NULL;";

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
     * Method to return a dog from the database from his graphical note serial id.
     * 
     * @param string $serial_id The graphical note serial id  
     * @return Appoitment A Appoitment model object containing all the result rows of the query 
     */
    public function findBySerialId(string $serial_id)
    {
        $statement = "
        SELECT id, datetime_appoitment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appoitment
        WHERE note_graphical_serial_id = :SERIAL_ID;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':SERIAL_ID', $serial_id, \PDO::PARAM_STR);
            $statement->execute();

            $appoitment = new Appoitment();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $appoitment->id = $result["id"];
                $appoitment->datetime_appoitment = $result["datetime_appoitment"];
                $appoitment->duration_in_hour = $result["duration_in_hour"];
                $appoitment->note_text = $result["note_text"];
                $appoitment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appoitment->summary = $result["summary"];
                $appoitment->user_id_customer = $result["user_id_customer"];
                $appoitment->user_id_educator = $result["user_id_educator"];
            }
            else{
                $appoitment = null;
            }

            return $appoitment;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a appoitment in the database.
     * 
     * @param Appoitment $appoitment The absence model object
     * @return int The number of rows affected by the insert
     */
    public function insert(Appoitment $appoitment)
    {
        $statement = "
        INSERT INTO appoitment (datetime_appoitment,duration_in_hour,
        user_id_customer,user_id_educator) 

        VALUES(:DATETIME_APPOITMENT, :DURATION_IN_HOUR,
        :USER_ID_CUSTOMER,:USER_ID_EDUCATOR);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATETIME_APPOITMENT', $appoitment->datetime_appoitment, \PDO::PARAM_STR);
            $statement->bindParam(':DURATION_IN_HOUR', $appoitment->duration_in_hour, \PDO::PARAM_INT);    
            $statement->bindParam(':USER_ID_CUSTOMER', $appoitment->user_id_customer, \PDO::PARAM_INT); 
            $statement->bindParam(':USER_ID_EDUCATOR', $appoitment->user_id_educator, \PDO::PARAM_INT);    
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
     * @param Appoitment $appoitment The absence model object
     * @return int The number of rows affected by the update
     */
    public function update(Appoitment $appoitment)
    {
        $statement = "
        UPDATE appoitment
        SET note_text = :NOTE_TEXT, 
        note_graphical_serial_id = :NOTE_GRAPHICAL_SERIAL_ID,
        summary = :SUMMARY
        WHERE id = :ID_APPOITMENT;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NOTE_TEXT', $appoitment->note_text, \PDO::PARAM_STR);
            $statement->bindParam(':NOTE_GRAPHICAL_SERIAL_ID', $appoitment->note_graphical_serial_id, \PDO::PARAM_STR);    
            $statement->bindParam(':SUMMARY', $appoitment->summary, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_APPOITMENT', $appoitment->id, \PDO::PARAM_INT);  
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
     * @param int $userId The user who deletes the appointment
     * @param int $id The appoitment identifier 
     * @return int The number of rows affected by the delete
     */
    public function delete(int $id,int $idUser)
    {
        $statement = "
        UPDATE appoitment
        SET datetime_deletion = NOW(), 
        user_id_deletion = :ID_USER
        WHERE id = :ID_APPOITMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $idUser, \PDO::PARAM_INT);
            $statement->bindParam(':ID_APPOITMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}