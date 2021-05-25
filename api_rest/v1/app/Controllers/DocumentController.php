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
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

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

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $document = $this->DAODocument->find($id);

        if (is_null($document)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($document);
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

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateDocument($document)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDocumentTypeFormat($document->type)) {
            return ResponseController::invalidDocumentTypeFormat();
        }

        $user = $this->DAOUser->find($document->user_id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        $filename = HelperController::generateRandomString();

        switch ($document->type) {
            case Constants::DOCUMENT_TYPE_CONDTIONS_OF_REGISTRATION:

                if (!$this->validateDocumentConditionsRegistration($document)) {
                    return ResponseController::unprocessableEntityResponse();
                }
    
                if (!HelperController::validatePackageNumber($document->package_number)) {
                    return ResponseController::packageNumberFormatProblem();
                }
    
                $package_number = $document->package_number;
                $date = date('d/m/Y');
                $document->document_serial_id = $filename;
                $signature_base64 = $document->signature_base64;
                $userfirstname = $user->firstname;
                $userlastname = $user->lastname;
    
                HelperController::storeConditionsRegistration($filename,$package_number,$date,$signature_base64,$userfirstname,$userlastname);

                $upload_dir = HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$filename.".pdf";
                
                break;

            case Constants::DOCUMENT_TYPE_POSTER:
               
                if (!isset($_FILES["document"])) {
                    return ResponseController::unprocessableEntityResponse();
                }

                if ($_FILES["document"]["type"] != Constants::TYPE_DOCUMENT_PDF) {
                    return ResponseController::documentTypeNotPdfProblem();
                }

                $tmp_file = $_FILES["document"]["tmp_name"];
                $upload_dir = HelperController::getDefaultDirectory()."storage/app/pdf/".$filename.".pdf";

                if (!move_uploaded_file($tmp_file,$upload_dir)) {
                    return ResponseController::uploadFailed();
                }

                break;
            default:
                return ResponseController::invalidDocumentTypeFormat();
                break;
        }

        $document->document_serial_id = $filename;

        $this->DAODocument->insert($document);

        HelperController::sendMail("Bonjour et merci de faire confiance à la société Douceur de Chien, vous trouverez ci-joint le document qui a été ajouté à votre compte, vous pouvez égalament accéder à ce document depuis votre compte.","Un nouveau document a été ajouté à votre compte",$user->email,$upload_dir);

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

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (!$userAuth ||  $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDocument = $this->DAODocument->find($document->id);

        if (is_null($actualDocument)) {
            return ResponseController::notFoundResponse();
        }

        $actualDocument->document_serial_id = $document->document_serial_id ?? $actualDocument->document_serial_id;
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

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (!$userAuth ||  $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $document = $this->DAODocument->find($id);

        if (is_null($document)) {
            return ResponseController::notFoundResponse();
        }

        switch ($document->type) {
            case Constants::DOCUMENT_TYPE_CONDTIONS_OF_REGISTRATION:
                $filename = HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$document->document_serial_id.".pdf";
                
                if (file_exists($filename)) {
                    unlink($filename);
                }
                break;
            
            case Constants::DOCUMENT_TYPE_POSTER:
               
                $filename = HelperController::getDefaultDirectory()."storage/app/pdf/".$document->document_serial_id.".pdf";
                
                if (file_exists($filename)) {
                    unlink($filename);
                }
                break;

            default:
                break;
        }  

        $this->DAODocument->delete($document);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to download a document.
     * 
     * @param int  $serial_id The document identifier
     * @return string The status and the body in JSON format of the response
     */
    public function downloadDocument(string $serial_id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::notFoundResponse();
        }

        if ($userAuth ||  $userAuth->code_role == Constants::ADMIN_CODE_ROLE) {
            $document = $this->DAODocument->findBySerialId($serial_id);
        }
        else{
            $document = $this->DAODocument->findByUserIdAndSerialId($userAuth->id, $serial_id);
        }

        if (is_null($document)) {
            return ResponseController::notFoundResponse();
        }

        header("Content-Type: application/pdf");

        switch ($document->type) {
            case Constants::DOCUMENT_TYPE_CONDTIONS_OF_REGISTRATION:
                
                $document_data = file_get_contents(HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$serial_id.".pdf");
                break;
            
            case Constants::DOCUMENT_TYPE_POSTER:
                
                $document_data = file_get_contents(HelperController::getDefaultDirectory()."storage/app/pdf/".$serial_id.".pdf");
                break;

            default:
                break;
        }

        return ResponseController::successfulRequestWithoutJson($document_data);
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
        if ($document->type == null) {
            return false;
        }

        if ($document->user_id == null) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if the conditions registration document required fields have been defined for the creation.
     * 
     * @param Document $document The document model object
     * @return bool
     */
    private function validateDocumentConditionsRegistration(Document $document)
    {
        if ($document->package_number == null) {
            return false;
        }

        if ($document->signature_base64 == null) {
            return false;
        }

        return true;
    }
}