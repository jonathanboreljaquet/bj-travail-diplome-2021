<?php
/**
 * Absence.php
 *
 * Absence model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Absence {

    private $db = null;

    /**
     * 
     * Constructor of the Absence object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }


    /**
     * 
     * Method to return all the absences of the database in an associative array.
     * 
     * @param bool $deleted Bool to define whether to search for existing or deleted absences
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll(bool $deleted,int $idEducator)
    {
        $statement ="
        SELECT id, date_absence_from, date_absence_to, description
        FROM absence
        WHERE is_deleted= :DELETED
        AND id_educator = :ID_EDUCATOR";
        
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
     * Method to return a absence from the database in an associative array.
     * 
     * @param int $id The absence identifier 
     * @param int $idEducator The educator identifier
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id,int $idEducator)
    {
        $statement = "
        SELECT id, date_absence_from, date_absence_to, description
        FROM absence
        WHERE id = :ID_ABSENCE
        AND is_deleted = 0
        AND id_educator = :ID_EDUCATOR;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_ABSENCE', $id, \PDO::PARAM_INT);
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
     * Method to insert a absence in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @param int $idEducator The educator identifier
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input,int $idEducator)
    {
        $statement = "
        INSERT INTO absence (date_absence_from, date_absence_to, description,id_educator ,is_deleted) 
        VALUES(STR_TO_DATE(:DATE_ABSENCE_FROM, \"%Y-%m-%d\"), STR_TO_DATE(:DATE_ABSENCE_TO, \"%Y-%m-%d\"), :DESCRIPTION,:ID_EDUCATOR ,0);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_ABSENCE_FROM', $input['date_absence_from'], \PDO::PARAM_STR);
            $statement->bindParam(':DATE_ABSENCE_TO', $input['date_absence_to'], \PDO::PARAM_STR);  
            $statement->bindParam(':DESCRIPTION', $input['description'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_EDUCATOR', $idEducator, \PDO::PARAM_INT);  
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
     * @param int $id The absence identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE absence
        SET date_absence_from = STR_TO_DATE(:DATE_ABSENCE_FROM, \"%Y-%m-%d\"), date_absence_to = STR_TO_DATE(:DATE_ABSENCE_TO, \"%Y-%m-%d\"), description = :DESCRIPTION
        WHERE id = :ID_ABSENCE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DATE_ABSENCE_FROM', $input['date_absence_from'], \PDO::PARAM_STR);
            $statement->bindParam(':DATE_ABSENCE_TO', $input['date_absence_to'], \PDO::PARAM_STR);  
            $statement->bindParam(':DESCRIPTION', $input['description'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_ABSENCE', $id, \PDO::PARAM_INT);
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
     * @param int $id The absence identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        UPDATE absence
        SET is_deleted = 1
        WHERE id = :ID_ABSENCE;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_ABSENCE', $id, \PDO::PARAM_INT);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}