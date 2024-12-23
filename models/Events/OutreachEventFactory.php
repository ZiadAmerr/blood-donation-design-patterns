<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

// Abstract Factory Class for creating different types of events

class OutreachEventFactory extends EventFactory{
    
    // returns an object of outreach event 
    public function eventFactory($args)
    {
        // extract arguments from the list args
        // return new OutreachEvent($title, $address, $dateTime, $activities, $listOfOrganizations);  
    } 

}

?>
