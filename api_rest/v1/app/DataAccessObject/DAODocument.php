<?php
/**
 * DAODocument.php
 *
 * Data access object of the document table.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\DataAccessObject;

use App\Models\Document;

class DAODocument {

    private \PDO $db;

    /**
     * 
     * Constructor of the DAODocument object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Method to return all the documents of the database in an array of document objects.
     * 
     * @return Document[] A Document object array
     */
    public function findAll()
    {
        $statement = "
        SELECT id, document_serial_number, type, user_id
        FROM document;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $documentArray = array();
            
            foreach ($results as $result) {
                $document = new Document();
                $document->id = $result["id"];
                $document->document_serial_number = $result["document_serial_number"];
                $document->type = $result["type"];
                $document->user_id = $result["user_id"];
                array_push($documentArray,$document);
            }

            return $documentArray;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }


    /**
     * 
     * Method to return a document from the database in a document model object.
     * 
     * @param int $id The document identifier 
     * @return Document A Document model object containing all the result rows of the query 
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

            $document = new Document();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $document->id = $result["id"];
                $document->document_serial_number = $result["document_serial_number"];
                $document->type = $result["type"];
                $document->user_id = $result["user_id"];
            }
            else{
                $document = null;
            }

            return $document;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return all documents from the database with the user id in an array of documents objects.
     * 
     * @param int $userId The user identifier 
     * @return Document[] A Document object array
     */
    public function findWithUserId(int $userId)
    {
        $statement = "
        SELECT id, document_serial_number, type, user_id 
        FROM document
        WHERE user_id = :ID_USER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
            
            $documentArray = array();
            
            foreach ($results as $result) {
                $document = new Document();
                $document->id = $result["id"];
                $document->document_serial_number = $result["document_serial_number"];
                $document->type = $result["type"];
                $document->user_id = $result["user_id"];
                array_push($documentArray,$document);
            }

            return $documentArray;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to return a document from the database with the user id and serial number in a document model object.
     * 
     * @param int $userId The user identifier 
     * @param string $serial_number The serial number of the document 
     * @return Document A Document model object containing all the result rows of the query 
     */
    public function findWithUserIdAndSerialNumber(int $userId,string $serial_number)
    {
        $statement = "
        SELECT id, document_serial_number, type, user_id 
        FROM document
        WHERE user_id = :ID_USER
        AND document_serial_number= :SERIAL_NUMBER;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_USER', $userId, \PDO::PARAM_INT);
            $statement->bindParam(':SERIAL_NUMBER', $serial_number, \PDO::PARAM_STR);
            $statement->execute();
            
            $document = new Document();

            if ($statement->rowCount()==1) {
                $result = $statement->fetch(\PDO::FETCH_ASSOC);
                $document->id = $result["id"];
                $document->document_serial_number = $result["document_serial_number"];
                $document->type = $result["type"];
                $document->user_id = $result["user_id"];
            }
            else{
                $document = null;
            }

            return $document;

        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    /**
     * 
     * Method to insert a document in the database.
     * 
     * @param Document $document The document model object
     * @return int The number of rows affected by the insert
     */
    public function insert(Document $document)
    {
        $statement = "
        INSERT INTO document (document_serial_number, type,user_id) 
        VALUES(:DOCUMENT_SERIAL_NUMBER, :TYPE, :USER_ID);";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DOCUMENT_SERIAL_NUMBER', $document->document_serial_number, \PDO::PARAM_STR);
            $statement->bindParam(':TYPE', $document->type, \PDO::PARAM_STR);    
            $statement->bindParam(':USER_ID', $document->user_id, \PDO::PARAM_STR);  
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
     * @param Document $document The document model object
     * @return int The number of rows affected by the update
     */
    public function update(Document $document)
    {
        $statement = "
        UPDATE document
        SET document_serial_number = :DOCUMENT_SERIAL_NUMBER, 
        type = :TYPE,
        user_id = :USER_ID
        WHERE id = :ID_DOCUMENT;";
        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':DOCUMENT_SERIAL_NUMBER', $document->document_serial_number, \PDO::PARAM_STR);
            $statement->bindParam(':TYPE', $document->type, \PDO::PARAM_STR);    
            $statement->bindParam(':USER_ID', $document->user_id, \PDO::PARAM_STR);  
            $statement->bindParam(':ID_DOCUMENT', $document->id, \PDO::PARAM_INT);
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
     * @param Document $document The document model object
     * @return int The number of rows affected by the delete
     */
    public function delete(Document $document)
    {
        $statement = "
        DELETE FROM document
        WHERE id = :ID_DOCUMENT;";

        try {
            $statement = $this->db->prepare($statement);
            $statement->bindParam(':ID_DOCUMENT', $document->id, \PDO::PARAM_INT);
            $statement->execute();
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}
