<?php
/**
 * AppoitmentController.php
 *
 * Controller of the Appoitment model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOAppoitment;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Appoitment;
use App\System\Constants;

class AppoitmentController {

    private DAOAppoitment $DAOAppoitment;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the AppoitmentController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOAppoitment = new DAOAppoitment($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all Appoitments in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllAppoitments()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        if($userAuth->code_role == Constants::ADMIN_CODE_ROLE){
            $allAppoitments = $this->DAOAppoitment->findAll(null,$userAuth->id);
        }
        else{
            $allAppoitments = $this->DAOAppoitment->findAll($userAuth->id,null);
        }
        
        return ResponseController::successfulRequest($allAppoitments);   
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