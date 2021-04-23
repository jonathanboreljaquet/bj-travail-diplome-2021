<?php
/**
 * UserController.php
 *
 * Controller of the User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOUser;
use App\Models\User;
use App\System\Constants;

class UserController {

    private DAOUser $DAOUser;

    /**
     * 
     * Constructor of the UserController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all users in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllUsers()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $api_token = $headers['Authorization'];

        $user = $this->DAOUser->getUserWithApiToken($api_token);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allUsers = $this->DAOUser->findAll(Constants::USER_CODE_ROLE);

        return ResponseController::successfulRequest($allUsers);  
    }

    /**
     * 
     * Method to return a user in JSON format.
     * 
     * @param int $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        
        $user = $this->DAOUser->getUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $result = $this->DAOUser->find($id);

        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to create a user.
     * 
     * @param array  $input The body of request
     * @return string The status and the body in JSON format of the response
     */
    public function createUser(array $input)
    {
        if (!$this->validateUser($input)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $email = $input["email"];
        $firstname = $input["firstname"];
        $lastname = $input["lastname"];
        $phonenumber = $input["phonenumber"];
        $address = $input["address"];
        $api_token = HelperController::generateApiToken();
        $code_role = Constants::USER_CODE_ROLE;

        if (isset($input["password"])) {
            $password_hash = password_hash($input["password"],PASSWORD_DEFAULT);
        }
        else{
            $random_password = HelperController::generateRandomPassword();
            $password_hash = password_hash($random_password,PASSWORD_DEFAULT);
        }

        $user = new User(null,$email,$firstname,$lastname,$phonenumber,$address,$api_token,$code_role,$password_hash);

        $this->DAOUser->insert($user);

        if (isset($random_password)) {
            HelperController::sendMail($random_password,$email);
        }

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a user.
     * 
     * @param array  $input The body of request
     * @param int  $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    public function updateUser(array $input, int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->DAOUser->getUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualUser = $this->DAOUser->find($id);

        if (!$actualUser) {
            return ResponseController::notFoundResponse();
        }

        $arrayActualuser = (array)$actualUser;

        $arrayNewUser = array_replace($arrayActualuser,$input);

        $newUser = new User();
        foreach ($arrayNewUser as $key => $value)
        {
            $newUser->$key = $value;
        }

        if (isset($input["password"])) {
            $newUser->password_hash = password_hash($input["password"],PASSWORD_DEFAULT);
        }

        $this->DAOUser->update($newUser);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a user.
     * 
     * @param int  $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->DAOUser->getUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        if (!$user) {
            return ResponseController::notFoundResponse();
        }

        $this->DAOUser->delete($user);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to get the api token of a user by logging in.
     * 
     * @param array  $input The body of request
     * @return string The status and the body in JSON format of the response
     */
    public function connection(array $input)
    {
        if (!isset($input["email"]) || !isset($input["password"])) {
            return ResponseController::unprocessableEntityResponse();
        }
        $user = $this->DAOUser->getUserWithEmail($input["email"]);

        if (!password_verify($input["password"],$user->password_hash)) {
            return ResponseController::invalidLogin();
        }
        
        $result = array();
        $result["api_token"] = $user->api_token;
        return ResponseController::successfulRequest($result);
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