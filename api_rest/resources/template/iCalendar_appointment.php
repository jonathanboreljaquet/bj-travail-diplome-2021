<?php
/**
 * iCalendar_appointment.php.php
 *
 * Template of the content of an ics file (iCalendar) used in the HelperController with the sendMailWithICSFile function
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$now = new DateTime('NOW');

echo "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//douceurdechien/handcal//NONSGML v1.0//FR
CALSCALE:GREGORIAN
METHOD:PUBLISH
BEGIN:VEVENT
UID:" . md5(time()) . "
DTSTAMP;TZID=/Europe/Berlin:" . gmdate("Ymd\THis",$now->getTimestamp() + 60*60*2) . "
DTSTART;TZID=/Europe/Berlin:".gmdate("Ymd\THis",$start_datetime->getTimestamp() + 60*60*2)."
DTEND;TZID=/Europe/Berlin:".gmdate("Ymd\THis",$end_datetime->getTimestamp() + 60*60*2)."
SUMMARY:Rendez-vous Douceur de Chien
LOCATION:701 Avenue de la Bigorre, 31210 Montréjeau, France
ORGANIZER:MAILTO:douceurdechien@douceurdechien.com
END:VEVENT
END:VCALENDAR";