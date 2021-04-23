<?php
/**
 * DogController.php
 *
 * Controller of the Dog model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\Dog;
use App\Controllers\ResponseController;
use App\Models\User;
use App\System\Constants;

class DogController {

    private $db;
    private $requestMethod;
    private $dogId;
    private $dog;
    private $user;


    /**
     * 
     * Constructor of the DogController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod  The request method (GET,POST,PATCH,DELETE)
     * @param int $dogId  The dog id
     */
    public function __construct(\PDO $db, string $requestMethod, int $dogId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->dogId = $dogId;
        $this->dog = new Dog($db);
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
                if ($this->dogId) {
                    $response = $this->getDog($this->dogId);
                } else {
                    $response = $this->getAllDogs();
                };
                break;
            case 'POST':
                $response = $this->createDog();
                break;
            case 'PATCH':
                $response = $this->updateDog($this->dogId);
                break;
            case 'DELETE':
                $response = $this->deleteDog($this->dogId);
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
     * Method to return all dogs in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllDogs()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $result = $this->dog->findAll();

        return ResponseController::successfulRequest($result);  
    }

        /**
     * 
     * Method to return a dog in JSON format.
     * 
     * @param int $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        
        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->dog->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a dog.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createDog()
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

        if (!$this->validateDog($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $this->dog->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a dog.
     * 
     * @param int  $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDog = $this->dog->find($id);

        if (!$actualDog) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        $newDog = array_replace($actualDog,$input);

        $this->dog->update($id,$newDog);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a dog.
     * 
     * @param int  $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->dog->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->dog->delete($id);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateDog(array $input)
    {
        if (!isset($input['name'])) {
            return false;
        }

        if (!isset($input['breed'])) {
            return false;
        }

        if (!isset($input['sex'])) {
            return false;
        }

        if (!isset($input['user_id'])) {
            return false;
        }

        return true;
    }
}