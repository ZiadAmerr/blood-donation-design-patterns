<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

// Abstract Factory Class for creating different types of events

class WorkShopEventFactory extends EventFactory{

    // returns an object of workshop event
    public function eventFactory($args)
    {
        // extract arguments from the list args
        // return new WorkShopEvent($title, $address, $dateTime, $instructor, $maxAttendees, $materials);  
    } 

}

?>
