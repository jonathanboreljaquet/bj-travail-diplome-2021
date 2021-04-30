<?php
/**
 * DAOAbsence.php
 *
 * Data access object of the absence table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\Absence;

class DAOAbsence {

    private $db = null;

    /**
     * 
     * Constructor of the DAOAbsence object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * 
     * Method to return all the absences of the database in an array of absence objects.
     * 
     * @param bool $isDeleted  Bool to define whether to search for existing or deleted absences
     * @param int $idEducator The educator identifier
     * @return Absence[] A Absence object array
     */
    public function findAll(bool $isDeleted,int $idEducator)
    {
        $statement ="
        SELECT id, date_absence_from, date_absence_to, description, id_educator
        FROM absence
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR";
        
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->bindParam(':DELETED', $isDeleted, \PDO::PARAM_BOOL);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $absenceArray = array();
            
            foreach ($results as $result) {
                $absence = new Absence();
                $absence->id = $result["id"];
                $absence->date_absence_from = $result["date_absence_from"];
                $absence->date_absence_to = $result["date_absence_to"];
                $absence->description = $result["description"];
                $absence->id_educator = $result["id_educator"];
                array_push($absenceArray,$absence);
            }

            return $absenceArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 
     * Method to return an absence from the database in a absence model object.
     * 
     * @param int $id The absence identifier 
     * @param int $idEducator The educator identifier
     * @return Absence A Absence model object containing all the result rows of the query 
     */
    public function find(int $id,int $idEducator)
    {
        $statement = "
        SELECT id, date_absence_from, date_absence_to, description, id_educator
        FROM absence
        WHERE id = :ID_ABSENCE
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_ABSENCE', $id, \PDO::PARAM_INT);
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);
            $statement->execute();
            
            $absence = new Absence();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $absence->id = $result["id"];
                $absence->date_absence_from = $result["date_absence_from"];
                $absence->date_absence_to = $result["date_absence_to"];
                $absence->description = $result["description"];
                $absence->id_educator = $result["id_educator"];
            }
            else{
                $absence = null;
            }

            return $absence;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

     /**
     * 
     * Method to insert a absence in the database.
     * 
     * @param Absence $absence The absence model object
     * @return int The number of rows affected by the insert
     */
    public function insert(Absence $absence)
    {
        $statement = "
        INSERT INTO absence (date_absence_from, date_absence_to, description,id_educator ,is_deleted) 
        VALUES(STR_TO_DATE(:DATE_ABSENCE_FROM, \"%Y-%m-%d\"), STR_TO_DATE(:DATE_ABSENCE_TO, \"%Y-%m-%d\"), :DESCRIPTION,:ID_EDUCATOR ,0);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_ABSENCE_FROM', $absence->date_absence_from, \PDO::PARAM_STR);
            $statement->bindParam(':DATE_ABSENCE_TO', $absence->date_absence_to, \PDO::PARAM_STR);  
            $statement->bindParam(':DESCRIPTION', $absence->description, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $absence->id_educator, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a absence in the database.
     * 
     * @param Absence $absence The absence model object
     * @return int The number of rows affected by the update
     */
    public function update(Absence $absence)
    {
        $statement = "
        UPDATE absence
        SET date_absence_from = STR_TO_DATE(:DATE_ABSENCE_FROM, \"%Y-%m-%d\"), date_absence_to = STR_TO_DATE(:DATE_ABSENCE_TO, \"%Y-%m-%d\"), description = :DESCRIPTION
        WHERE id = :ID_ABSENCE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_ABSENCE_FROM', $absence->date_absence_from, \PDO::PARAM_STR);
            $statement->bindParam(':DATE_ABSENCE_TO', $absence->date_absence_to, \PDO::PARAM_STR);  
            $statement->bindParam(':DESCRIPTION', $absence->description, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_ABSENCE', $absence->id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a absence in the database.
     * 
     * @param Absence $absence The absence model object
     * @return int The number of rows affected by the delete
     */
    public function delete(Absence $absence)
    {
        $statement = "
        UPDATE absence
        SET is_deleted = 1
        WHERE id = :ID_ABSENCE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_ABSENCE', $absence->id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}