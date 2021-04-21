<?php
/**
 * TimeSlotController.php
 *
 * Controller of the TimeSlot model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\User;
use App\Models\TimeSlot;
use App\Models\WeeklySchedule;
use App\Models\ScheduleOverride;
use App\Controllers\HelperController;
use App\Controllers\ResponseController;
use App\System\Constants;

class TimeSlotController {

    private $db;
    private $requestMethod;
    private $timeSlotId;
    private $returnPlanning;
    private $timeSlot;
    private $user;
    private $weeklySchedule;
    private $scheduleOverride;


    /**
     * 
     * Constructor of the TimeSlotController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod The request method (GET,POST,PATCH,DELETE)
     * @param int $timeSlotId The time slot id
     * @param bool $returnPlanning Bool to define if the final planning should be searched
     */
    public function __construct(\PDO $db, string $requestMethod, int $timeSlotId = null, bool $returnPlanning = false)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->timeSlotId = $timeSlotId;
        $this->returnPlanning = $returnPlanning;
        $this->timeSlot = new TimeSlot($db);
        $this->user = new User($db);
        $this->weeklySchedule = new WeeklySchedule($db);
        $this->scheduleOverride = new ScheduleOverride($db);
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
                $response = $this->getAllTimeSlots();
                if ($this->timeSlotId) {
                    $response = $this->getTimeSlot($this->timeSlotId);
                }
                if($this->returnPlanning)
                {
                    $response = $this->getPlanningTimeSlots();
                }
                break;
            case 'POST':
                $response = $this->createTimeSlot();
                break;
            case 'PATCH':
                $response = $this->updateTimeSlot($this->timeSlotId);
                break;
            case 'DELETE':
                $response = $this->deleteTimeSlot($this->timeSlotId);
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
     * Method to return all time slots in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllTimeSlots()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       
        $result = $this->timeSlot->findAll(false, $user["id"]);        
        
        return ResponseController::successfulRequest($result);  
    }

    /**
     * 
     * Method to return a time slot in JSON format.
     * 
     * @param int  $id The time slot identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getTimeSlot(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->timeSlot->find($id, $user["id"]);
        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a time slot.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createTimeSlot()
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

        if (!$this->validateTimeSlot($input, $user["id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateTimeFormat($input["time_start"]) || !HelperController::validateTimeFormat($input["time_end"]) ) {
            return ResponseController::invalidTimeFormat();
        }

        if (!HelperController::validateCodeDayFormat($input["code_day"])) {
            return ResponseController::invalidCodeDayFormat();
        }

        if (!HelperController::validateChornologicalTime($input["time_start"],$input["time_end"])) {
            return ResponseController::chronologicalDateProblem();
        }

        if ($this->timeSlot->findOverlapInWeeklySchedule($input, $user["id"]))
        {
            return ResponseController::timeOverlapProblem();
        }

        $this->timeSlot->insert($input, $user["id"]);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a time slot.
     * 
     * @param int  $id The time slot identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateTimeSlot(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->timeSlot->find($id, $user["id"]);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateTimeSlot($input, $user["id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateTimeFormat($input["time_start"]) || !HelperController::validateTimeFormat($input["time_end"]) ) {
            return ResponseController::invalidTimeFormat();
        }

        if (!HelperController::validateCodeDayFormat($input["code_day"])) {
            return ResponseController::invalidCodeDayFormat();
        }

        if (!HelperController::validateChornologicalTime($input["time_start"],$input["time_end"])) {
            return ResponseController::chronologicalDateProblem();
        }

        if ($this->timeSlot->findOverlapInWeeklySchedule($input, $user["id"]))
        {
            return ResponseController::timeOverlapProblem();
        }

        $this->timeSlot->update($id,$input);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a time slot.
     * 
     * @param int  $id The time slot identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteTimeSlot(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->timeSlot->find($id, $user["id"]);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->timeSlot->delete($id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to return all available valid time slots with their corresponding dates in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getPlanningTimeSlots()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       
        $result = $this->timeSlot->findPlanningTimeSlots($user["id"]);        
        
        return ResponseController::successfulRequest($result);  
    }

    /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateTimeSlot(array $input, int $idEducator)
    {
        if (!isset($input['code_day'])) {
            return false;
        }

        if (!isset($input['time_start'])) {
            return false;
        }

        if (!isset($input['time_end'])) {
            return false;
        }

        if (!(isset($input['id_weekly_schedule']) xor isset($input['id_schedule_override']))) {
            return false;
        }

        if (isset($input['id_weekly_schedule'])) {
            $result = $this->weeklySchedule->find($input['id_weekly_schedule'], $idEducator);
        }

        if (isset($input['id_schedule_override'])) {
            $result = $this->scheduleOverride->find($input['id_schedule_override'],$idEducator);
        }
        
        if (!$result) {
            return false;
        }

        return true;
    }
}