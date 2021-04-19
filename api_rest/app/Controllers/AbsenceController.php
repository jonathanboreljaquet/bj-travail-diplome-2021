<?php
/**
 * AbsenceController.php
 *
 * Controller of the Absence model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\Absence;
use App\Models\User;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;

class AbsenceController {

    private $db;
    private $requestMethod;
    private $absenceId;
    private $absence;
    private $user;


    /**
     * 
     * Constructor of the AbsenceController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod The request method (GET,POST,PATCH,DELETE)
     * @param int $absenceId The absence id
     */
    public function __construct(\PDO $db, string $requestMethod, int $absenceId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->absenceId = $absenceId;
        $this->absence = new Absence($db);
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
                if ($this->absenceId) {
                    $response = $this->getAbsence($this->absenceId);
                } else {
                    $response = $this->getAllAbsences();
                };
                break;
            case 'POST':
                $response = $this->createAbsence();
                break;
            case 'PATCH':
                $response = $this->updateAbsence($this->absenceId);
                break;
            case 'DELETE':
                $response = $this->deleteAbsence($this->absenceId);
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
     * Method to return all absences in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllAbsences()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
       
        $result = $this->absence->findAll(false);        
        
        return ResponseController::successfulRequest($result);  
    }

    /**
     * 
     * Method to return a absence in JSON format.
     * 
     * @param int  $id The absence identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getAbsence(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->absence->find($id);
        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a absence.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createAbsence()
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

        if (!$this->validateAbsence($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_absence_from"]) || !HelperController::validateDateFormat($input["date_absence_to"]) ) {
            return ResponseController::invalidDateFormat();
        }

        if (!HelperController::validateChornologicalTime($input["date_absence_from"],$input["date_absence_to"])) {
            return ResponseController::chronologicalDateProblem();
        }

        $this->absence->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a absence.
     * 
     * @param int  $id The absence identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateAbsence(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->absence->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateAbsence($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        if (!HelperController::validateDateFormat($input["date_absence_from"]) || !HelperController::validateDateFormat($input["date_absence_to"]) ) {
            return ResponseController::invalidDateFormat();
        }

        if (!HelperController::validateChornologicalTime($input["date_absence_from"],$input["date_absence_to"])) {
            return ResponseController::chronologicalDateProblem();
        }

        $this->absence->update($id,$input);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a absence.
     * 
     * @param int  $id The absence identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteAbsence($id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->absence->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->absence->delete($id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateAbsence($input)
    {
        if (!isset($input['date_absence_from'])) {
            return false;
        }

        if (!isset($input['date_absence_to'])) {
            return false;
        }

        return true;
    }
}