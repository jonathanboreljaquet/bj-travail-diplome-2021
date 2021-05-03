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
    public ?string $description;
    public ?int $id_educator;

    /**
     * 
     * Constructor of the Absence model object.
     * 
     * @param int $id The Absence identifier
     * @param string $date_absence_from The start date of the absences
     * @param string $date_absence_to The end date of the absences
     * @param string $description The description of the absences
     * @param int $id_educator The educator identifier
     */
    public function __construct(int $id = null, string $date_absence_from = null, string $date_absence_to = null,string $description = null,int $id_educator = null)
    {
        $this->id = $id;
        $this->date_absence_from = $date_absence_from;
        $this->date_absence_to = $date_absence_to;
        $this->description = $description;
        $this->id_educator = $id_educator;
    }
}