<?php
/**
 * UserController.php
 *
 * Controller of the User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\Models\User;
use App\Controllers\ResponseController;
use App\Models\Dog;
use App\System\Constants;

class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $returnConnexion;
    private $user;
    private $dog;


    /**
     * 
     * Constructor of the UserController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod  The request method (GET,POST,PATCH,DELETE)
     * @param int $userId  The user id
     * @param bool $returnConnexion Bool to define if is the connexion endpoint
     */
    public function __construct(\PDO $db, string $requestMethod, int $userId = null, bool $returnConnexion = false)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        $this->user = new User($db);
        $this->dog = new Dog($db);
        $this->returnConnexion = $returnConnexion;
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
                $response = $this->getAllUsers();
                if ($this->userId) {
                    $response = $this->getUser($this->userId);
                }
                if($this->returnConnexion)
                {
                    $response = $this->connexion();
                }
                break;
            case 'POST':
                $response = $this->createUser();
                break;
            case 'PATCH':
                $response = $this->updateUser($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
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
     * Method to return all users in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    private function getAllUsers()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allUsers = $this->user->findAll();

        $usersWithDogs = array();

        foreach ($allUsers as $user) {
            $dogs = $this->dog->findWithUserId($user["id"]);
            $user["dogs"] = $dogs;
            array_push($usersWithDogs,$user);
        }

        return ResponseController::successfulRequest($usersWithDogs);  
    }

    
    /**
     * 
     * Method to return a user in JSON format.
     * 
     * @param int $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    private function getUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        
        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->user->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a user.
     * 
     * @return string The status and the body in JSON format of the response
     */
    private function createUser()
    {
        parse_str(file_get_contents('php://input'), $input);

        if (!$this->validateUser($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $this->user->insert($input);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a user.
     * 
     * @param int  $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    private function updateUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualUser = $this->user->find($id);

        if (!$actualUser) {
            return ResponseController::notFoundResponse();
        }

        parse_str(file_get_contents('php://input'), $input);

        $newUser = array_replace($actualUser,$input);

        $this->user->update($id,$newUser);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a user.
     * 
     * @param int  $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    private function deleteUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->user->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->user->delete($id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to connect a user.
     * 
     * @param int  $email The email of user 
     * @return string $password The password of user
     */
    private function connexion()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->user->getUser($headers['Authorization']);

        if (!$user || intval($user["code_role"]) != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->user->find($id);

        if (!$result) {
            return ResponseController::notFoundResponse();
        }

        $this->user->delete($id);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the required fields have been defined.
     * 
     * @param array $input The associative table of the query fields 
     * @return bool
     */
    private function validateUser(array $input)
    {
        if (!isset($input['email'])) {
            return false;
        }

        if (!isset($input['firstname'])) {
            return false;
        }

        if (!isset($input['lastname'])) {
            return false;
        }

        if (!isset($input['phonenumber'])) {
            return false;
        }

        if (!isset($input['address'])) {
            return false;
        }

        return true;
    }
}