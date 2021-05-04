<?php
/**
 * TimeSlotController.php
 *
 * Controller of the TimeSlot model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOTimeSlot;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\DataAccessObject\DAOScheduleOverride;
use App\DataAccessObject\DAOWeeklySchedule;
use App\Models\TimeSlot;
use App\System\Constants;

class TimeSlotController {

    private DAOTimeSlot $DAOTimeSlot;
    private DAOWeeklySchedule $DAOWeeklySchedule;
    private DAOScheduleOverride $DAOScheduleOverride;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the TimeSlotController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOTimeSlot = new DAOTimeSlot($db);
        $this->DAOWeeklySchedule = new DAOWeeklySchedule($db);
        $this->DAOScheduleOverride = new DAOScheduleOverride($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all time slots in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllTimeSlots()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allTimeSlots = $this->DAOTimeSlot->findAll(false,$userAuth->id);

        return ResponseController::successfulRequest($allTimeSlots);   
    }

    /**
     * 
     * Method to return a time slot in JSON format.
     * 
     * @param int  $id The time slot identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getTimeSlot(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $timeSlot = $this->DAOTimeSlot->find($id,$userAuth->id);

        if (is_null($timeSlot)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($timeSlot);
    }

    /**
     * 
     * Method to create a time slot.
     * 
     * @param TimeSlot $timeSlot The timeSlot model object
     * @return string The status and the body in JSON format of the response
     */
    public function createTimeSlot(TimeSlot $timeSlot)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $timeSlot->id_educator = $userAuth->id;

        if (!$this->validateTimeSlot($timeSlot)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateCodeDayFormat($timeSlot->code_day)) {
            return ResponseController::invalidCodeDayFormat();
        }

        if (!HelperController::validateTimeFormat($timeSlot->time_start) || !HelperController::validateTimeFormat($timeSlot->time_end) ) {
            return ResponseController::invalidTimeFormat();
        }

        if (!HelperController::validateChornologicalTime($timeSlot->time_start,$timeSlot->time_end)) {
            return ResponseController::chronologicalDateProblem();
        }

        if ($this->DAOTimeSlot->findOverlapInWeeklySchedule($timeSlot) || $this->DAOTimeSlot->findOverlapInScheduleOverride($timeSlot))
        {
            return ResponseController::timeOverlapProblem();
        }

        $this->DAOTimeSlot->insert($timeSlot);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a time slot.
     * 
     * @param TimeSlot $timeSlot The timeSlot model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateTimeSlot(TimeSlot $timeSlot)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualTimeSlot = $this->DAOTimeSlot->find($timeSlot->id,$userAuth->id);

        if (is_null($actualTimeSlot)) {
            return ResponseController::notFoundResponse();
        }

        $actualTimeSlot->code_day = $timeSlot->code_day ?? $actualTimeSlot->code_day;
        $actualTimeSlot->time_start = $timeSlot->time_start ?? $actualTimeSlot->time_start;
        $actualTimeSlot->time_end = $timeSlot->time_end ?? $actualTimeSlot->time_end;

        if (!HelperController::validateTimeFormat($actualTimeSlot->time_start) || !HelperController::validateTimeFormat($actualTimeSlot->time_end) ) {
            return ResponseController::invalidTimeFormat();
        }

        if (!HelperController::validateCodeDayFormat($actualTimeSlot->code_day)) {
            return ResponseController::invalidCodeDayFormat();
        }

        if (!HelperController::validateChornologicalTime($actualTimeSlot->time_start,$actualTimeSlot->time_end)) {
            return ResponseController::chronologicalDateProblem();
        }

        if ($this->DAOTimeSlot->findOverlapInWeeklySchedule($actualTimeSlot) || $this->DAOTimeSlot->findOverlapInScheduleOverride($actualTimeSlot))
        {
            return ResponseController::timeOverlapProblem();
        }

        $this->DAOTimeSlot->update($actualTimeSlot);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a time slot.
     * 
     * @param int  $id The time slot identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteTimeSlot(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $timeSlot = $this->DAOTimeSlot->find($id,$userAuth->id);

        if (is_null($timeSlot)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAOTimeSlot->delete($timeSlot);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to return all available valid time slots with their corresponding dates in JSON format.
     * 
     * @param int  $idUser The user identifier
     * @return string The status and the body in json format of the response
     */
    public function getPlanningTimeSlots(int $idUser)
    {

        $user = $this->DAOUser->find($idUser);

        if (is_null($user) || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::notFoundResponse();
        }

        $planning = $this->DAOTimeSlot->findPlanningForEducator($user->id);        
        
        return ResponseController::successfulRequest($planning);  
    }

    /**
     * 
     * Method to check if the time slot required fields have been defined for the creation.
     * 
     * @param TimeSlot $timeSlot The timeslot model object
     * @return bool
     */
    private function validateTimeSlot(TimeSlot $timeSlot)
    {
        if ($timeSlot->code_day == null) {
            return false;
        }

        if ($timeSlot->time_start == null) {
            return false;
        }

        if ($timeSlot->time_end == null) {
            return false;
        }

        if (($timeSlot->id_weekly_schedule != null) xor ($timeSlot->id_schedule_override == null)) {
            return false;
        }

        $result = null;

        if ($timeSlot->id_weekly_schedule != null) {
            $result = $this->DAOWeeklySchedule->find($timeSlot->id_weekly_schedule, $timeSlot->id_educator);
        }

        if ($timeSlot->id_schedule_override != null) {
            $result = $this->DAOScheduleOverride->find($timeSlot->id_schedule_override, $timeSlot->id_educator);
        }
        
        if ($result == null) {
            return false;
        }

        return true;
    }
}