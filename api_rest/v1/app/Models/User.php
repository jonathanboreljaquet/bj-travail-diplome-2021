<?php
/**
 * User.php
 *
 * User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class User {

    public ?int $id;
    public ?string $email;
    public ?string $firstname;
    public ?string $lastname;
    public ?string $phonenumber;
    public ?string $address;
    public ?string $api_token;
    public ?int $code_role;
    public ?string $password_hash;

    /**
     * 
     * Constructor of the User model object.
     * 
     * @param int $id The user identifier
     * @param string $email The email of the user
     * @param string $firstname the first name of the user
     * @param string $lastname the last name of the user
     * @param string $phonenumber The phone number of the user
     * @param string $address The address of the user
     * @param string $api_token The api_token of the user
     * @param int $code_role The code_role of the user
     * @param string $password_hash The password_hash of the user
     */
    public function __construct(int $id = null, string $email = null, string $firstname = null,
     string $lastname = null, string $phonenumber = null, string $address = null, string $api_token = null, int $code_role = null, string $password_hash = null)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phonenumber = $phonenumber;
        $this->address = $address;
        $this->api_token = $api_token;
        $this->code_role = $code_role;
        $this->password_hash = $password_hash;
    }
}