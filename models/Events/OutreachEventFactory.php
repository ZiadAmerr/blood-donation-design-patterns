<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/OutreachEvent.php";


class OutreachEventFactory extends EventFactory{
    
    public function eventFactory($args): ?Event {
        
        $title = $args['title'] ?? 'Untitled Event';
        $address = $args['address'] ?? null;
        $dateTime = $args['date_time'] ?? null;
        $activities = $args['activities'] ?? [];
        $maxAttendees = $args['max_attendees'] ?? 0;
        $listOfOrganizations = $args['list_of_organizations'] ?? [];

        return new OutreachEvent($title, $maxAttendees, $dateTime, $address, $activities, $listOfOrganizations);

    } 

}

?>
