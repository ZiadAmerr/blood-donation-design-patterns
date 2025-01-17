<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";
require_once "OutreachEvent.php";

class OutreachEventFactory extends EventFactory{
    
    // Create and return an OutreachEvent object
    public function eventFactory($args): ?Event {
        
        // Extract arguments from $args
        $title = $args['title'] ?? 'Untitled Event';
        $address = $args['address'] ?? null;
        $dateTime = $args['date_time'] ?? null;
        $activities = $args['activities'] ?? [];
        $maxAttendees = $args['max_attendees'] ?? 0;
        $listOfOrganizations = $args['list_of_organizations'] ?? [];


        // Return the created OutreachEvent object
        return new OutreachEvent($title, $maxAttendees, $dateTime, $address, $activities, $listOfOrganizations);

    } 

}

?>
