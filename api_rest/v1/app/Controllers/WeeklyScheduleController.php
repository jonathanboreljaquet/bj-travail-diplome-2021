<?php
/**
 * WeeklyScheduleController.php
 *
 * Controller of the WeeklySchedule model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOWeeklySchedule;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\WeeklySchedule;
use App\System\Constants;

class WeeklyScheduleController {

    private DAOWeeklySchedule $DAOWeeklySchedule;
    private DAOUser $DAOUser;

    /**
     * 
     * Constructor of the WeeklyScheduleController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOWeeklySchedule = new DAOWeeklySchedule($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all weekly schedules in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllWeeklySchedules()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allWeeklySchedules = $this->DAOWeeklySchedule->findAll(false,$userAuth->id);

        return ResponseController::successfulRequest($allWeeklySchedules);   
    }

    /**
     * 
     * Method to return a weekly schedule in JSON format.
     * 
     * @param int  $id The weekly schedule identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getWeeklySchedule(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $weeklySchedule = $this->DAOWeeklySchedule->find($id,$userAuth->id);

        if (is_null($weeklySchedule)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($weeklySchedule);
    }

    /**
     * 
     * Method to create a weekly schedule.
     * 
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return string The status and the body in JSON format of the response
     */
    public function createWeeklySchedule(WeeklySchedule $weeklySchedule)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $weeklySchedule->id_educator = $userAuth->id;

        if (!$this->validateWeeklySchedule($weeklySchedule)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($weeklySchedule->date_valid_from)) {
            return ResponseController::invalidDateFormat();
        }

        if (!is_null($weeklySchedule->date_valid_to)) {
            if (!HelperController::validateDateFormat($weeklySchedule->date_valid_to)) {
                return ResponseController::invalidDateFormat();
            }
            if (!HelperController::validateChornologicalTime($weeklySchedule->date_valid_from,$weeklySchedule->date_valid_to)) {
                return ResponseController::chronologicalDateProblem();
            }
        }
        else{
            if ($this->DAOWeeklySchedule->findActifPermanentSchedule($weeklySchedule)) {
                return ResponseController::permanentScheduleAlreadyExist();
            }
        }   

        if ($this->DAOWeeklySchedule->findOverlap($weeklySchedule)) {
            return ResponseController::dateOverlapProblem();
        }

        $this->DAOWeeklySchedule->insert($weeklySchedule);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a weekly schedule.
     * 
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateWeeklySchedule(WeeklySchedule $weeklySchedule)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualWeeklySchedule = $this->DAOWeeklySchedule->find($weeklySchedule->id,$userAuth->id);

        if (is_null($actualWeeklySchedule)) {
            return ResponseController::notFoundResponse();
        }

        $actualWeeklySchedule->date_valid_from = $weeklySchedule->date_valid_from ?? $actualWeeklySchedule->date_valid_from;
        $actualWeeklySchedule->date_valid_to = $weeklySchedule->date_valid_to;


        if (!HelperController::validateDateFormat($actualWeeklySchedule->date_valid_from)) {
            return ResponseController::invalidDateFormat();
        }

        if (!is_null($actualWeeklySchedule->date_valid_to)) {
            if (!HelperController::validateDateFormat($actualWeeklySchedule->date_valid_to)) {
                return ResponseController::invalidDateFormat();
            }
            if (!HelperController::validateChornologicalTime($actualWeeklySchedule->date_valid_from,$actualWeeklySchedule->date_valid_to)) {
                return ResponseController::chronologicalDateProblem();
            }
        }
        else{
            if ($this->DAOWeeklySchedule->findActifPermanentSchedule($actualWeeklySchedule)) {
                return ResponseController::permanentScheduleAlreadyExist();
            }
        }  

        if ($this->DAOWeeklySchedule->findOverlap($actualWeeklySchedule)) {
            return ResponseController::dateOverlapProblem();
        }

        $this->DAOWeeklySchedule->update($actualWeeklySchedule);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a weekly schedule.
     * 
     * @param int  $id The weekly schedule identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteWeeklySchedule(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $weeklySchedule = $this->DAOWeeklySchedule->find($id,$userAuth->id);

        if (is_null($weeklySchedule)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAOWeeklySchedule->delete($weeklySchedule);

        return ResponseController::successfulRequest(null);
    }

   /**
     * 
     * Method to check if the weekly schedule required fields have been defined for the creation.
     * 
     * @param WeeklySchedule $weeklySchedule The weeklyschedule model object
     * @return bool
     */
    private function validateWeeklySchedule(WeeklySchedule $weeklySchedule)
    {
        if ($weeklySchedule->date_valid_from == null) {
            return false;
        }

        return true;
    }
}