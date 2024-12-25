<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";
require_once "FundRaiserEvent.php";

class FundRaiserEventFactory extends EventFactory{
    
    // create an object of Fundraiser event and return it
    public function eventFactory($args): ?Event
    {
        // extract arguments from the list args
        $title = $args['title'] ?? 'Untitled Event';
        $address = $args['address'] ?? null;
        $dateTime = $args['date_time'] ?? null;
        $goalAmount = $args['goal_amount'] ?? 0;
        $raisedAmount = $args['goal_amount'] ?? 0;

        return new FundraiserEvent($title, $address, $dateTime, $goalAmount, $raisedAmount); 
         
    } 

}

?>
