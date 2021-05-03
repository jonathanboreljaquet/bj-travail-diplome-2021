<?php
/**
 * Appoitment.php
 *
 * Appoitment model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class Appoitment {

    public ?int $id;
    public ?string $datetime_appoitment;
    public ?int $duration_in_hour;
    public ?string $note_text;
    public ?string $note_graphical_serial_id;
    public ?string $summary;
    public ?int $user_id_customer;
    public ?int $user_id_educator;

    /**
     * 
     * Constructor of the Appoitment model object.
     * 
     * @param int $id The Appoitment identifier
     * @param string $datetime_appoitment The date of the appoitment
     * @param int $duration_in_hour The duration of the appoitment in hours
     * @param string $note_text The text note of the appoitment
     * @param string $note_graphical_serial_id The serial id of the graphical note
     * @param string $summary The educator identifier
     * @param int $user_id_customer The customer identifier
     * @param int $user_id_educator The educator identifier
     */
    public function __construct(int $id = null, string $datetime_appoitment = null, int $duration_in_hour = null,string $note_text = null,string $note_graphical_serial_id = null,string $summary = null, int $user_id_customer = null, int $user_id_educator = null)
    {
        $this->id = $id;
        $this->datetime_appoitment = $datetime_appoitment;
        $this->duration_in_hour = $duration_in_hour;
        $this->note_text = $note_text;
        $this->note_graphical_serial_id = $note_graphical_serial_id;
        $this->summary = $summary;
        $this->user_id_customer = $user_id_customer;
        $this->user_id_educator = $user_id_educator;
    }
}