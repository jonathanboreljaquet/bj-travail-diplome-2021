<?php
/**
 * TimeSlot.php
 *
 * TimeSlot model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
namespace App\Models;

class TimeSlot {

    public ?int $id;
    public ?int $code_day;
    public ?string $time_start;
    public ?string $time_end;
    public ?int $id_weekly_schedule;
    public ?int $id_schedule_override;
    public ?int $id_educator;

    /**
     * 
     * Constructor of the TimeSlot model object.
     * 
     * @param int $id The TimeSlot identifier
     * @param int $code_day The day code of a week (1 = Sunday, 2 = Monday, 3 = Tuesday, 4 = Wednesday, 5 = Thursday, 6 = Friday, 7 = Saturday)
     * @param string $time_start The start time of the time slot
     * @param string $time_end The end time of the time slot
     * @param int $id_weekly_schedule The weekly schedule identifier
     * @param int $id_schedule_override The schedule override identifier
     * @param int $id_educator The educator identifier
     */
    public function __construct(int $id = null, int $code_day = null, string $time_start = null, string $time_end = null, int $id_weekly_schedule = null, int $id_schedule_override = null, int $id_educator = null)
    {
        $this->id = $id;
        $this->code_day = $code_day;
        $this->time_start = $time_start;
        $this->time_end = $time_end;
        $this->id_weekly_schedule = $id_weekly_schedule;
        $this->id_schedule_override = $id_schedule_override;
        $this->id_educator = $id_educator;
    }
}