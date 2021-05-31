<?php
/**
 * ScheduleOverride.php
 *
 * ScheduleOverride model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class ScheduleOverride {

    public ?int $id;
    public ?string $date_schedule_override;
    public ?int $id_educator;

    /**
     * 
     * Constructor of the ScheduleOverride model object.
     * 
     * @param int $id The ScheduleOverride identifier
     * @param string $date_valid_from The date of the schedule override
     * @param int $id_educator The educator identifier
     */
    public function __construct(int $id = null, string $date_schedule_override = null ,int $id_educator = null)
    {
        $this->id = $id;
        $this->date_schedule_override = $date_schedule_override;
        $this->id_educator = $id_educator;
    }
}