<?php
/**
 * DAOAppointment.php
 *
 * Data access object of the appointment table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\Appointment;

class DAOAppointment {

    private $db = null;

    /**
     * 
     * Constructor of the DAOAppointment object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the appointments of the database in an array of appointment objects.
     * 
     * @param int $userCustomerId The customer identifier 
     * @param int $userEducatorId The educator identifier 
     * @return Appointment[] A Appointment object array
     */
    public function findAll(int $userCustomerId = null, int $userEducatorId = null)
    {
        $statement = "
        SELECT id, datetime_appointment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appointment
		WHERE (user_id_customer = :ID_USER_CUSTOMER OR user_id_educator = :ID_USER_EDUCATOR)
		AND datetime_deletion IS NULL
		AND user_id_deletion IS NULL";
 
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER_CUSTOMER', $userCustomerId, \PDO::PARAM_INT);
            $statement->bindParam(':ID_USER_EDUCATOR', $userEducatorId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $appointmentArray = array();    

            foreach ($results as $result) {
                $appointment = new Appointment();
                $appointment->id = $result["id"];
                $appointment->datetime_appointment = $result["datetime_appointment"];
                $appointment->duration_in_hour = $result["duration_in_hour"];
                $appointment->note_text = $result["note_text"];
                $appointment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appointment->summary = $result["summary"];
                $appointment->user_id_customer = $result["user_id_customer"];
                $appointment->user_id_educator = $result["user_id_educator"];
                array_push($appointmentArray,$appointment);
            }

            return $appointmentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return a appointment slot from the database in a appointment model object.
     * 
     * @param int $id The appointment identifier 
     * @param int $idEducator The educator identifier
     * @return Appointment A Appointment model object containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, datetime_appointment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appointment
        WHERE id = :ID_APPOINTMENT";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_APPOINTMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            
            $appointment = new Appointment();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $appointment->id = $result["id"];
                $appointment->datetime_appointment = $result["datetime_appointment"];
                $appointment->duration_in_hour = $result["duration_in_hour"];
                $appointment->note_text = $result["note_text"];
                $appointment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appointment->summary = $result["summary"];
                $appointment->user_id_customer = $result["user_id_customer"];
                $appointment->user_id_educator = $result["user_id_educator"];
            }
            else{
                $appointment = null;
            }

            return $appointment;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return all appointment from the database with the user id in an array of appointment objects without the administrator data.
     * 
     * @param int $userId The user identifier 
     * @return Appointment[] A Appointment object array
     */
    public function findByUserId(int $userId)
    {
        $statement = "
        SELECT id, datetime_appointment,duration_in_hour, summary,
        user_id_customer,user_id_educator
        FROM appointment
        WHERE user_id_customer = :ID_USER
        AND datetime_deletion IS NULL
        AND user_id_deletion IS NULL
        ORDER BY datetime_appointment DESC";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $appointmentArray = array();    

            foreach ($results as $result) {
                $appointment = new Appointment();
                $appointment->id = $result["id"];
                $appointment->datetime_appointment = $result["datetime_appointment"];
                $appointment->duration_in_hour = $result["duration_in_hour"];
                $appointment->summary = $result["summary"];
                $appointment->user_id_customer = $result["user_id_customer"];
                $appointment->user_id_educator = $result["user_id_educator"];
                array_push($appointmentArray,$appointment);
            }

            return $appointmentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return all appointment from the database with the user id in an array of appointment objects.
     * 
     * @param int $userId The user identifier 
     * @return Appointment[] A Appointment object array
     */
    public function findByUserIdForAdmin(int $userId)
    {
        $statement = "
        SELECT id, datetime_appointment,duration_in_hour,note_text,note_graphical_serial_id, summary,
        user_id_customer,user_id_educator
        FROM appointment
        WHERE user_id_customer = :ID_USER
        AND datetime_deletion IS NULL
        AND user_id_deletion IS NULL
        ORDER BY datetime_appointment DESC";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

            $appointmentArray = array();    

            foreach ($results as $result) {
                $appointment = new Appointment();
                $appointment->id = $result["id"];
                $appointment->datetime_appointment = $result["datetime_appointment"];
                $appointment->duration_in_hour = $result["duration_in_hour"];
                $appointment->note_text = $result["note_text"];
                $appointment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appointment->summary = $result["summary"];
                $appointment->user_id_customer = $result["user_id_customer"];
                $appointment->user_id_educator = $result["user_id_educator"];
                array_push($appointmentArray,$appointment);
            }

            return $appointmentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a appointment from the database from his graphical note serial id.
     * 
     * @param string $serial_id The graphical note serial id  
     * @return Appointment A Appointment model object containing all the result rows of the query 
     */
    public function findBySerialId(string $serial_id)
    {
        $statement = "
        SELECT id, datetime_appointment,duration_in_hour, note_text, 
        note_graphical_serial_id, summary, datetime_deletion,
        user_id_customer,user_id_educator,user_id_deletion
        FROM appointment
        WHERE note_graphical_serial_id = :SERIAL_ID";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':SERIAL_ID', $serial_id, \PDO::PARAM_STR);
            $statement->execute();

            $appointment = new Appointment();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $appointment->id = $result["id"];
                $appointment->datetime_appointment = $result["datetime_appointment"];
                $appointment->duration_in_hour = $result["duration_in_hour"];
                $appointment->note_text = $result["note_text"];
                $appointment->note_graphical_serial_id = $result["note_graphical_serial_id"];
                $appointment->summary = $result["summary"];
                $appointment->user_id_customer = $result["user_id_customer"];
                $appointment->user_id_educator = $result["user_id_educator"];
            }
            else{
                $appointment = null;
            }

            return $appointment;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a appointment in the database.
     * 
     * @param Appointment $appointment The absence model object
     * @return int The number of rows affected by the insert
     */
    public function insert(Appointment $appointment)
    {
        $statement = "
        INSERT INTO appointment (datetime_appointment,duration_in_hour,
        user_id_customer,user_id_educator) 

        VALUES(:DATETIME_APPOINTMENT, :DURATION_IN_HOUR,
        :USER_ID_CUSTOMER,:USER_ID_EDUCATOR)";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATETIME_APPOINTMENT', $appointment->datetime_appointment, \PDO::PARAM_STR);
            $statement->bindParam(':DURATION_IN_HOUR', $appointment->duration_in_hour, \PDO::PARAM_INT);    
            $statement->bindParam(':USER_ID_CUSTOMER', $appointment->user_id_customer, \PDO::PARAM_INT); 
            $statement->bindParam(':USER_ID_EDUCATOR', $appointment->user_id_educator, \PDO::PARAM_INT);    
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a appointment in the database.
     * 
     * @param Appointment $appointment The absence model object
     * @return int The number of rows affected by the update
     */
    public function update(Appointment $appointment)
    {
        $statement = "
        UPDATE appointment
        SET note_text = :NOTE_TEXT, 
        note_graphical_serial_id = :NOTE_GRAPHICAL_SERIAL_ID,
        summary = :SUMMARY
        WHERE id = :ID_APPOINTMENT";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':NOTE_TEXT', $appointment->note_text, \PDO::PARAM_STR);
            $statement->bindParam(':NOTE_GRAPHICAL_SERIAL_ID', $appointment->note_graphical_serial_id, \PDO::PARAM_STR);    
            $statement->bindParam(':SUMMARY', $appointment->summary, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_APPOINTMENT', $appointment->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a appointment in the database.
     * 
     * @param int $userId The user who deletes the appointment
     * @param int $id The appointment identifier 
     * @return int The number of rows affected by the delete
     */
    public function delete(int $id,int $idUser)
    {
        $statement = "
        UPDATE appointment
        SET datetime_deletion = NOW(), 
        user_id_deletion = :ID_USER
        WHERE id = :ID_APPOINTMENT";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $idUser, \PDO::PARAM_INT);
            $statement->bindParam(':ID_APPOINTMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}