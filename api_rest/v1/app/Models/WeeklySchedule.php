<?php
/**
 * WeeklySchedule.php
 *
 * WeeklySchedule model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class WeeklySchedule {

    public ?int $id;
    public ?string $date_valid_from;
    public ?string $date_valid_to;
    public ?int $id_educator;

    /**
     * 
     * Constructor of the WeeklySchedule model object.
     * 
     * @param int $id The WeeklySchedule identifier
     * @param string $date_valid_from The start date of the weekly schedule
     * @param string $date_valid_to The end date of the weekly schedule
     * @param int $id_educator The educator identifier
     */
    public function __construct(int $id = null, string $date_valid_from = null, string $date_valid_to = null,int $id_educator = null)
    {
        $this->id = $id;
        $this->date_valid_from = $date_valid_from;
        $this->date_valid_to = $date_valid_to;
        $this->id_educator = $id_educator;
    }
}