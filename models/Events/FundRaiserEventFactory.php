<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/FundRaiserEvent.php";

class FundRaiserEventFactory extends EventFactory{
    
    public function eventFactory($args): ?Event
    {
        // extract arguments from the list args
        $title = $args['title'] ?? 'Untitled Event';
        $address = $args['address'] ?? null;
        $dateTime = $args['date_time'] ?? null;
        $maxAttendees = $args['max_attendees'] ?? 0;
        $goalAmount = $args['goal_amount'] ?? 0;
        $raisedAmount = $args['goal_amount'] ?? 0;

        return new FundraiserEvent($title, $maxAttendees, $dateTime, $address, $goalAmount, $raisedAmount); 
         
    } 

}

?>
