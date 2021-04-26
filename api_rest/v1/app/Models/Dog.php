<?php
/**
 * Dog.php
 *
 * Dog model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Dog {

    public ?int $id;
    public ?string $name;
    public ?string $breed;
    public ?string $sex;
    public ?string $picture_serial_number;
    public ?string $chip_id;
    public ?int $user_id;

    /**
     * 
     * Constructor of the Dog model object.
     * 
     * @param int $id The dog identifier
     * @param string $name The name of the dog
     * @param string $breed The breed of the dog
     * @param string $sex The sex of the dog
     * @param string $picture_serial_number The picture serial number of the dog
     * @param string $chip_id The chip id of the dog
     * @param int $user_id The user id of the dog's owner
     */
    public function __construct(int $id = null, string $name = null, string $breed = null,
     string $sex = null, string $picture_serial_number = null, string $chip_id = null, int $user_id = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->breed = $breed;
        $this->sex = $sex;
        $this->picture_serial_number = $picture_serial_number;
        $this->chip_id = $chip_id;
        $this->user_id = $user_id;
    }
}