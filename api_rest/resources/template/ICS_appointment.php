<?php
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);


echo 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//douceurdechien/handcal//NONSGML v1.0//FR
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:Europe/Berlin
X-LIC-LOCATION:Europe/Berlin
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE
BEGIN:VEVENT
UID:' . md5(date_format($datetime_start, 'Ymd\This')) . '
DTSTAMP:' . time() . '
LOCATION:' . addslashes('701 Avenue de la Bigorre, 31210 Montréjeau, France') . '
SUMMARY:' . addslashes($title) . '
DTSTART;TZID=Europe/Berlin:' . date_format($datetime_start, 'Ymd\This') . '
DTEND;TZID=Europe/Berlin:' . date_format($datetime_end, 'Ymd\This') . '
END:VEVENT
END:VCALENDAR';
?>