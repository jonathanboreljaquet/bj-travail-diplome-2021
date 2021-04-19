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

class UserController {

    private $db;
    private $requestMethod;
    private $userId;
    private $user;


    /**
     * 
     * Constructor of the UserController object.
     * 
     * @param PDO $db The database connection
     * @param string $requestMethod  The request method (GET,POST,PATCH,DELETE)
     * @param int $userId  The user id
     */
    public function __construct(\PDO $db, string $requestMethod, int $userId = null)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
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
                if ($this->userId) {
                    $response = $this->getUser($this->userId);
                } else {
                    $response = $this->getAllUsers();
                };
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

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $result = $this->user->findAll();

        return ResponseController::successfulRequest($result);  
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

        $role = $this->user->getRole($headers['Authorization']);

        if ($role != ResponseController::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->user->find($id);
        if (!$result) {
            return ResponseController::notFoundResponse();
        }
        
        return ResponseController::successfulRequest($result);
    }
}