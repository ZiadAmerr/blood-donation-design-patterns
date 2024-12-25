<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";
require_once "WorkshopEvent.php";

class WorkShopEventFactory extends EventFactory{

    // Create and return a WorkShopEvent object
    public function eventFactory($args): ?Event {
        // Extract arguments from $args
        $title = $args['title'] ?? 'Untitled Workshop';
        $address = $args['address_id'] ?? null;
        $dateTime = $args['date_time'] ?? null;
        $instructor = $args['instructor'] ?? 'Unknown Instructor';
        $maxAttendees = $args['max_attendees'] ?? 0;
        $materials = $args['materials'] ?? [];

        // Return the created WorkShopEvent object
        return new WorkShopEvent($title, $address, $dateTime, $instructor, $maxAttendees, $materials);
    }


}

?>
