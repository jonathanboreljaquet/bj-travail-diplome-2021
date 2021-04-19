<?php
/**
 * WeeklyScheduleController.php
 *
 * Controller of the WeeklySchedule model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\WeeklySchedule;
use App\Models\User;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;

class WeeklyScheduleController {

    private $db;
    private $requestMethod;
    private $weeklyScheduleId;
    private $weeklySchedule;
    private $user;


    /**
     * 
     * Constructor of the WeeklyScheduleController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod The request method (GET,POST,PATCH,DELETE)
     * @param int $weeklyScheduleId The weekly schedule id
     */
    public function __construct(\PDO $db, string $requestMethod, int $weeklyScheduleId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->weeklyScheduleId = $weeklyScheduleId;
        $this->weeklySchedule = new WeeklySchedule($db);
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
                if ($this->weeklyScheduleId) {
                    $response = $this->getWeeklySchedule($this->weeklyScheduleId);
                } else {
                    $response = $this->getAllWeeklySchedules();
                };
                break;
            case 'POST':
                $response = $this->createWeeklySchedule();
                break;
            case 'PATCH':
                $response = $this->updateWeeklySchedule($this->weeklyScheduleId);
                break;
            case 'DELETE':
                $response = $this->deleteWeeklySchedule($this->weeklyScheduleId);
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
     * Method to return all weekly schedules in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllWeeklySchedules()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       
        $result = $this->weeklySchedule->findAll(false);        
        
        return ResponseController::successfulRequest($result);  
    }

    /**
     * 
     * Method to return a weekly schedule in JSON format.
     * 
     * @param int  $id The weekly schedule identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getWeeklySchedule(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->weeklySchedule->find($id);
        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a weekly schedule.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createWeeklySchedule()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateWeeklySchedule($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_valid_from"])) {
            return ResponseController::invalidDateFormat();
        }

        if (isset($input["date_valid_to"])) {
            if (!HelperController::validateDateFormat($input["date_valid_to"])) {
                return ResponseController::invalidDateFormat();
            }
            if (!HelperController::validateChornologicalTime($input["date_valid_from"],$input["date_valid_to"])) {
                return ResponseController::chronologicalDateProblem();
            }
        }
        else{
            if ($this->weeklySchedule->findActifPermanentSchedule()) {
                return ResponseController::permanentScheduleAlreadyExist();
            }
        }   

        if ($this->weeklySchedule->findOverlap($input)) {
            return ResponseController::dateOverlapProblem();
        }

        $this->weeklySchedule->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a weekly schedule.
     * 
     * @param int  $id The weekly schedule identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateWeeklySchedule(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->weeklySchedule->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateWeeklySchedule($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_valid_from"])) {
            return ResponseController::invalidDateFormat();
        }

        if (isset($input["date_valid_to"])) {
            if (!HelperController::validateDateFormat($input["date_valid_to"])) {
                return ResponseController::invalidDateFormat();
            }
            if (!HelperController::validateChornologicalTime($input["date_valid_from"],$input["date_valid_to"])) {
                return ResponseController::chronologicalDateProblem();
            }
        }
        else{
            if ($this->weeklySchedule->findActifPermanentSchedule()) {
                return ResponseController::permanentScheduleAlreadyExist();
            }
        }  

        if ($this->weeklySchedule->findOverlap($input)) {
            return ResponseController::dateOverlapProblem();
        }

        $this->weeklySchedule->update($id,$input);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a weekly schedule.
     * 
     * @param int  $id The weekly schedule identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteWeeklySchedule(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->weeklySchedule->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->weeklySchedule->delete($id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateWeeklySchedule(array $input)
    {
        if (!isset($input['date_valid_from'])) {
            return false;
        }

        return true;
    }
}