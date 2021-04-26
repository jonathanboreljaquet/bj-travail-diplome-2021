<?php
/**
 * UserController.php
 *
 * Controller of the User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAODog;
use App\DataAccessObject\DAOUser;
use app\Models\User;
use App\Controllers\ResponseController;
use App\System\Constants;
class UserController {

    private DAOUser $DAOUser;
    private DAODog $DAODog;

    /**
     * 
     * Constructor of the UserController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOUser = new DAOUser($db);
        $this->DAODog = new DAODog($db);
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

        $user = $this->DAOUser->findUserWithApiToken($api_token);

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

        
        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        return ResponseController::successfulRequest($user);
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
        $user = new User();
        $user->email = $input["email"] ?? null;
        $user->firstname = $input["firstname"] ?? null;
        $user->lastname = $input["lastname"] ?? null;
        $user->phonenumber = $input["phonenumber"] ?? null;
        $user->address = $input["address"] ?? null;
        $user->api_token = HelperController::generateApiToken();
        $user->code_role = Constants::USER_CODE_ROLE;
        

        if (!$this->validateUser($user)) {
            return ResponseController::unprocessableEntityResponse();
        } 

        if (!HelperController::validateEmailFormat($user->email)) {
            return ResponseController::invalidEmailFormat();
        }

        if (isset($input["password"])) {
            $user->password_hash = password_hash($input["password"],PASSWORD_DEFAULT);
        }
        else{
            $random_password = HelperController::generateRandomPassword();
            $user->password_hash = password_hash($random_password,PASSWORD_DEFAULT);
        }

        $this->DAOUser->insert($user);

        if (isset($random_password)) {
            HelperController::sendMail($random_password,$user->mail);
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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualUser = $this->DAOUser->find($id);

        if (!$actualUser) {
            return ResponseController::notFoundResponse();
        }

        $modifiedUser = new User();
        $modifiedUser->id = $actualUser->id;
        $modifiedUser->email = $input["email"] ?? $actualUser->email;
        $modifiedUser->firstname = $input["firstname"] ?? $actualUser->firstname;
        $modifiedUser->lastname = $input["lastname"] ?? $actualUser->lastname;
        $modifiedUser->phonenumber = $input["phonenumber"] ?? $actualUser->phonenumber;
        $modifiedUser->address = $input["address"] ?? $actualUser->address;

        if (!HelperController::validateEmailFormat($modifiedUser->email)) {
            return ResponseController::invalidEmailFormat();
        }

        $this->DAOUser->update($modifiedUser);

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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

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
        $user = $this->DAOUser->findUserWithEmail($input["email"]);

        if (!$user) {
            return ResponseController::invalidLogin();
        }

        if (!password_verify($input["password"],$user->password_hash)) {
            return ResponseController::invalidLogin();
        }

        $user->api_token = HelperController::generateApiToken();

        $this->DAOUser->updateApiToken($user);
        
        $result = array();
        $result["api_token"] = $user->api_token;
        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to return all information of the currently logged in user in JSON format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public function getMyInformations()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }
        
        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);
        $dog = $this->DAODog->findWithUserId($user->id);
        $user->dogs = $dog;
        
        
        return ResponseController::successfulRequest($user);
    }

     /**
     * 
     * Method to check if the user required fields have been defined for the creation.
     * 
     * @param User $user The user model object
     * @return bool
     */
    private function validateUser(User $user)
    {
        if ($user->email == null) {
            return false;
        }

        if ($user->firstname == null) {
            return false;
        }

        if ($user->lastname == null) {
            return false;
        }

        if ($user->phonenumber == null) {
            return false;
        }

        if ($user->address == null) {
            return false;
        }

        return true;
    }
}