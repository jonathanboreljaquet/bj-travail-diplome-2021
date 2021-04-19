<?php
/**
 * ScheduleOverrideController.php
 *
 * Controller of the ScheduleOverride model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\ScheduleOverride;
use App\Models\User;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;

class ScheduleOverrideController {

    private $db;
    private $requestMethod;
    private $scheduleOverrideId;
    private $scheduleOverride;
    private $user;


    /**
     * 
     * Constructor of the ScheduleOverrideController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod The request method (GET,POST,PATCH,DELETE)
     * @param int $scheduleOverrideId The schedule override id
     */
    public function __construct(\PDO $db, string $requestMethod, int $scheduleOverrideId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->scheduleOverrideId = $scheduleOverrideId;
        $this->scheduleOverride = new scheduleOverride($db);
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
                if ($this->scheduleOverrideId) {
                    $response = $this->getScheduleOverride($this->scheduleOverrideId);
                } else {
                    $response = $this->getAllScheduleOverrides();
                };
                break;
            case 'POST':
                $response = $this->createScheduleOverride();
                break;
            case 'PATCH':
                $response = $this->updateScheduleOverride($this->scheduleOverrideId);
                break;
            case 'DELETE':
                $response = $this->deleteScheduleOverride($this->scheduleOverrideId);
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
     * Method to return all schedule overrides in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllScheduleOverrides()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       
        $result = $this->scheduleOverride->findAll(false,$user["id"]);        
        
        return ResponseController::successfulRequest($result);  
    }

    /**
     * 
     * Method to return a schedule override in JSON format.
     * 
     * @param int  $id The schedule override identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getScheduleOverride(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       

        $result = $this->scheduleOverride->find($id,$user["id"]);
        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a schedule override.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createScheduleOverride()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateScheduleOverride($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_schedule_override"])) {
            return ResponseController::invalidDateFormat();
        }

        if ($this->scheduleOverride->findExistence($input["date_schedule_override"],$user["id"])) {
            return ResponseController::dateOverlapProblem();
        }

        $this->scheduleOverride->insert($input,$user["id"]);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a schedule override.
     * 
     * @param int  $id The schedule override identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateScheduleOverride(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       

        $result = $this->scheduleOverride->find($id,$user["id"]);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateScheduleOverride($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_schedule_override"])) {
            return ResponseController::invalidDateFormat();
        }

        if ($this->scheduleOverride->findExistence($input["date_schedule_override"],$user["id"])) {
            return ResponseController::dateOverlapProblem();
        }

        $this->scheduleOverride->update($id,$input);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a schedule override.
     * 
     * @param int  $id The schedule override identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteScheduleOverride(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       

        $result = $this->scheduleOverride->find($id,$user["id"]);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->scheduleOverride->delete($id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateScheduleOverride(array $input)
    {
        if (!isset($input['date_schedule_override'])) {
            return false;
        }

        return true;
    }
}