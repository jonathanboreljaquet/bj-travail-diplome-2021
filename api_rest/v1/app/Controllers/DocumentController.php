<?php
/**
 * DocumentController.php
 *
 * Controller of the Document model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\Document;
use App\Controllers\ResponseController;
use App\Models\User;
use App\System\Constants;

class DocumentController {

    private $db;
    private $requestMethod;
    private $documentId;
    private $document;
    private $user;


    /**
     * 
     * Constructor of the DocumentController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod  The request method (GET,POST,PATCH,DELETE)
     * @param int $documentId  The document id
     */
    public function __construct(\PDO $db, string $requestMethod, int $documentId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->documentId = $documentId;
        $this->document = new Document($db);
        $this->user = new User($db);
    }

    /**
     * 
     * Method allowing to proceed to the request chosen during the creation of the object.
     * 
     */
    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->documentId) {
                    $response = $this->getDocument($this->documentId);
                } else {
                    $response = $this->getAllDocuments();
                };
                break;
            case 'POST':
                $response = $this->createDocument();
                break;
            case 'PATCH':
                $response = $this->updateDocument($this->documentId);
                break;
            case 'DELETE':
                $response = $this->deleteDocument($this->documentId);
                break;
            default:
                $response = ResponseController::notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    /**
     * 
     * Method to return all documents in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllDocuments()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $result = $this->document->findAll();

        return ResponseController::successfulRequest($result);  
    }

        /**
     * 
     * Method to return a document in JSON format.
     * 
     * @param int $id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getDocument(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        
        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->document->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a document.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createDocument()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateDocument($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDocumentTypeFormat($input["type"])) {
            return ResponseController::invalidDocumentTypeFormat();
        }

        $this->document->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a document.
     * 
     * @param int  $id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateDocument(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDocument = $this->document->find($id);


        if (!$actualDocument) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        $newDocument = array_replace($actualDocument,$input);

        if (!HelperController::validateDocumentTypeFormat($newDocument["type"])) {
            return ResponseController::invalidDocumentTypeFormat();
        }

        $this->document->update($id,$newDocument);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a document.
     * 
     * @param int  $id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteDocument(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->document->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->document->delete($id);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateDocument(array $input)
    {
        if (!isset($input['document_serial_number'])) {
            return false;
        }

        if (!isset($input['type'])) {
            return false;
        }

        if (!isset($input['user_id'])) {
            return false;
        }

        return true;
    }
}