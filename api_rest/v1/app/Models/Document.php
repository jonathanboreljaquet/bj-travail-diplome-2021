<?php
/**
 * Document.php
 *
 * Document model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Document {

    private $db = null;

    /**
     * 
     * Constructor of the Document object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the documents of the database in an associative array.
     * 
     * @return array The associative array containing all the result rows of the query 
     */
    public function findAll()
    {
        $statement = "
        SELECT id, document_serial_number, type, user_id
        FROM document;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 
     * Method to return a document from the database in an associative array.
     * 
     * @param int $id The document identifier 
     * @return array The associative array containing all the result rows of the query 
     */
    public function find(int $id)
    {
        $statement = "
        SELECT id, document_serial_number, type, user_id
        FROM document
        WHERE id = :ID_DOCUMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOCUMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a document in the database.
     * 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the insert
     */
    public function insert(array $input)
    {
        $statement = "
        INSERT INTO document (document_serial_number, type,user_id) 
        VALUES(:DOCUMENT_SERIAL_NUMBER, :TYPE, :USER_ID);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DOCUMENT_SERIAL_NUMBER', $input['document_serial_number'], \PDO::PARAM_STR);
            $statement->bindParam(':TYPE', $input['type'], \PDO::PARAM_STR);    
            $statement->bindParam(':USER_ID', $input['user_id'], \PDO::PARAM_STR);  
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to update a document in the database.
     * 
     * @param int $id The document identifier 
     * @param array $input The associative table with the corresponding keys and values 
     * @return int The number of rows affected by the update
     */
    public function update(int $id, array $input)
    {
        $statement = "
        UPDATE document
        SET document_serial_number = :DOCUMENT_SERIAL_NUMBER, 
        type = :TYPE,
        user_id = :USER_ID
        WHERE id = :ID_DOCUMENT;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DOCUMENT_SERIAL_NUMBER', $input['document_serial_number'], \PDO::PARAM_STR);
            $statement->bindParam(':TYPE', $input['type'], \PDO::PARAM_STR);    
            $statement->bindParam(':USER_ID', $input['user_id'], \PDO::PARAM_STR);  
            $statement->bindParam(':ID_DOCUMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to delete a document in the database.
     * 
     * @param int $id The document identifier 
     * @return int The number of rows affected by the update
     */
    public function delete(int $id)
    {
        $statement = "
        DELETE FROM document
        WHERE id = :ID_DOCUMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOCUMENT', $id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}