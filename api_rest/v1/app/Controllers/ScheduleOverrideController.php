<?php
/**
 * ScheduleOverrideController.php
 *
 * Controller of the ScheduleOverride model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOScheduleOverride;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\DataAccessObject\DAOTimeSlot;
use App\Models\ScheduleOverride;
use App\System\Constants;

class ScheduleOverrideController {

    private DAOScheduleOverride $DAOScheduleOverride;
    private DAOTimeSlot $DAOTimeSlot;
    private DAOUser $DAOUser;

    /**
     * 
     * Constructor of the ScheduleOverrideController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOScheduleOverride = new DAOScheduleOverride($db);
        $this->DAOTimeSlot = new DAOTimeSlot($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all schedule overrides and their time slots in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllScheduleOverrides()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }
        
        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $allScheduleOverrides = $this->DAOScheduleOverride->findAll(false,$userAuth->id);

        foreach ($allScheduleOverrides as $scheduleOverride) {
            $scheduleOverride->timeSlots = $this->DAOTimeSlot->findAllByIdScheduleOverride(false, $userAuth->id, $scheduleOverride->id);
        }   
        
        return ResponseController::successfulRequest($allScheduleOverrides);
    }

    /**
     * 
     * Method to return a schedule override in JSON format.
     * 
     * @param int  $id The schedule override identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getScheduleOverride(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $scheduleOverride = $this->DAOScheduleOverride->find($id,$userAuth->id);

        if (is_null($scheduleOverride)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($scheduleOverride);
    }

    /**
     * 
     * Method to create a schedule override.
     * 
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return string The status and the body in JSON format of the response
     */
    public function createScheduleOverride(ScheduleOverride $scheduleOverride)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $scheduleOverride->id_educator = $userAuth->id;

        if (!$this->validateScheduleOverride($scheduleOverride)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($scheduleOverride->date_schedule_override)) {
            return ResponseController::invalidDateFormat();
        }

        if ($this->DAOScheduleOverride->findExistence($scheduleOverride)) {
            return ResponseController::dateOverlapProblem();
        }

        $this->DAOScheduleOverride->insert($scheduleOverride);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to delete a schedule override.
     * 
     * @param int  $id The schedule override identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteScheduleOverride(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $scheduleOverride = $this->DAOScheduleOverride->find($id,$userAuth->id);

        if (is_null($scheduleOverride)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAOScheduleOverride->delete($scheduleOverride);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to check if the schedule override required fields have been defined for the creation.
     * 
     * @param ScheduleOverride $scheduleOverride The scheduleoverride model object
     * @return bool
     */
    private function validateScheduleOverride(ScheduleOverride $scheduleOverride)
    {
        if ($scheduleOverride->date_schedule_override == null) {
            return false;
        }

        return true;
    }
}