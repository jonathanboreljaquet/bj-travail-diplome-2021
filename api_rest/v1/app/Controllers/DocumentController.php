<?php
/**
 * DocumentController.php
 *
 * Controller of the Document model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAODocument;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Document;
use App\System\Constants;

class DocumentController {

    private DAODocument $DAODocument;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the DocumentController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAODocument = new DAODocument($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all documents in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllDocuments()
    {
        HelperController::storeConditionsRegistration("321321");
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allDocuments = $this->DAODocument->findAll();

        return ResponseController::successfulRequest($allDocuments);  
    }

    /**
     * 
     * Method to return a document in JSON format.
     * 
     * @param int $id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getDocument(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->DAODocument->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a document.
     * 
     * @param Document $document The document model object
     * @return string The status and the body in JSON format of the response
     */
    public function createDocument(Document $document)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateDocument($document)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDocumentTypeFormat($document->type)) {
            return ResponseController::invalidDocumentTypeFormat();
        }

        if ($document->type == Constants::DOCUMENT_TYPE_CONDTIONS_OF_REGISTRATION) {
            //Upload function
        }

        $user = $this->DAOUser->find($document->user_id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAODocument->insert($document);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a document.
     * 
     * @param Document $document The document model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateDocument(Document $document)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$userAuth ||  $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDocument = $this->DAODocument->find($document->id);

        if (is_null($actualDocument)) {
            return ResponseController::notFoundResponse();
        }

        $actualDocument->document_serial_number = $document->document_serial_number ?? $actualDocument->document_serial_number;
        $actualDocument->type = $document->type ?? $actualDocument->type;

        if (!HelperController::validateDocumentTypeFormat($actualDocument->type)) {
            return ResponseController::invalidDocumentTypeFormat();
        }

        $this->DAODocument->update($actualDocument);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a document.
     * 
     * @param int  $id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteDocument(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$userAuth ||  $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $document = $this->DAODocument->find($id);

        if (is_null($document)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAODocument->delete($document);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the document required fields have been defined for the creation.
     * 
     * @param Document $document The document model object
     * @return bool
     */
    private function validateDocument(Document $document)
    {
        if ($document->document_serial_number == null) {
            return false;
        }

        if ($document->type == null) {
            return false;
        }

        if ($document->user_id == null) {
            return false;
        }

        return true;
    }
}