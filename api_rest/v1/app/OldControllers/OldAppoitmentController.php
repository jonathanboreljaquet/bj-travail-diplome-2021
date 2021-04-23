<?php
/**
 * AppoitmentController.php
 *
 * Controller of the Appoitment model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\Appoitment;
use App\Controllers\ResponseController;
use App\Models\User;
use App\System\Constants;

class AppoitmentController {

    private $db;
    private $requestMethod;
    private $appoitmentId;
    private $appoitment;
    private $user;


    /**
     * 
     * Constructor of the AppoitmentController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod  The request method (GET,POST,PATCH,DELETE)
     * @param int $appoitmentId  The appoitment id
     */
    public function __construct(\PDO $db, string $requestMethod, int $appoitmentId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->appoitmentId = $appoitmentId;
        $this->appoitment = new Appoitment($db);
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
                if ($this->appoitmentId) {
                    $response = $this->getAppoitment($this->appoitmentId);
                } else {
                    $response = $this->getAllAppoitments();
                };
                break;
            case 'POST':
                $response = $this->createAppoitment();
                break;
            case 'PATCH':
                $response = $this->updateAppoitment($this->appoitmentId);
                break;
            case 'DELETE':
                $response = $this->deleteAppoitment($this->appoitmentId);
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
     * Method to return all Appoitments in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllAppoitments()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $result = $this->appoitment->findAll();

        return ResponseController::successfulRequest($result);  
    }

        /**
     * 
     * Method to return a appoitment in JSON format.
     * 
     * @param int $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getAppoitment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        
        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->appoitment->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a appoitment.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createAppoitment()
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

        if (!$this->validateAppoitment($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $this->appoitment->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a appoitment.
     * 
     * @param int  $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateAppoitment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualAppoitment = $this->appoitment->find($id);


        if (!$actualAppoitment) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        $newAppoitment = array_replace($actualAppoitment,$input);

        $this->appoitment->update($id,$newAppoitment);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a appoitment.
     * 
     * @param int  $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteAppoitment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->appoitment->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->appoitment->delete($id,$user["id"]);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateAppoitment(array $input)
    {
        if (!isset($input['datetime_appoitment'])) {
            return false;
        }

        if (!isset($input['duration_in_hour'])) {
            return false;
        }

        if (!isset($input['user_id_customer'])) {
            return false;
        }

        if (!isset($input['user_id_educator'])) {
            return false;
        }

        return true;
    }
}