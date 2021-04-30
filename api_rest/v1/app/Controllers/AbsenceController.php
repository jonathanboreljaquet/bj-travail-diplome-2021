<?php
/**
 * AbsenceController.php
 *
 * Controller of the Absence model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOAbsence;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Absence;
use App\System\Constants;

class AbsenceController {

    private DAOAbsence $DAOAbsence;
    private DAOUser $DAOUser;

    /**
     * 
     * Constructor of the AbsenceController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOAbsence = new DAOAbsence($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all absences in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllAbsences()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allAbsences = $this->DAOAbsence->findAll(false,$userAuth->id);

        return ResponseController::successfulRequest($allAbsences);   
    }

    /**
     * 
     * Method to return a absence in JSON format.
     * 
     * @param int  $id The absence identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getAbsence(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $absence = $this->DAOAbsence->find($id,$userAuth->id);

        if (is_null($absence)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($absence);
    }

    /**
     * 
     * Method to create a absence.
     * 
     * @param Absence $absence The absence model object
     * @return string The status and the body in JSON format of the response
     */
    public function createAbsence(Absence $absence)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $absence->id_educator = $userAuth->id;

        if (!$this->validateAbsence($absence)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($absence->date_absence_from) || !HelperController::validateDateFormat($absence->date_absence_to) ) {
            return ResponseController::invalidDateFormat();
        }

        if (!HelperController::validateChornologicalTime($absence->date_absence_from,$absence->date_absence_to)) {
            return ResponseController::chronologicalDateProblem();
        }

        $this->DAOAbsence->insert($absence);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a absence.
     * 
     * @param Absence $absence The absence model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateAbsence(Absence $absence)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualAbsence = $this->DAOAbsence->find($absence->id,$userAuth->id);

        if (is_null($actualAbsence)) {
            return ResponseController::notFoundResponse();
        }

        $actualAbsence->date_absence_from = $absence->date_absence_from ?? $actualAbsence->date_absence_from;
        $actualAbsence->date_absence_to = $absence->date_absence_to ?? $actualAbsence->date_absence_to;
        $actualAbsence->description = $absence->description ?? $actualAbsence->description;

        if (!HelperController::validateDateFormat($absence->date_absence_from) || !HelperController::validateDateFormat($absence->date_absence_to) ) {
            return ResponseController::invalidDateFormat();
        }

        if (!HelperController::validateChornologicalTime($absence->date_absence_from,$absence->date_absence_to)) {
            return ResponseController::chronologicalDateProblem();
        }

        $this->DAOAbsence->update($actualAbsence);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a absence.
     * 
     * @param int  $id The absence identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteAbsence($id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $absence = $this->DAOAbsence->find($id,$userAuth->id);

        if (is_null($absence)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAOAbsence->delete($absence);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the absence required fields have been defined for the creation.
     * 
     * @param Absence $absence The absence model object
     * @return bool
     */
    private function validateAbsence(Absence $absence)
    {
        if ($absence->date_absence_from == null) {
            return false;
        }

        if ($absence->date_absence_to == null) {
            return false;
        }

        if ($absence->id_educator == null) {
            return false;
        }

        return true;
    }
}