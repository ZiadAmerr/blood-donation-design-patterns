<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

// Abstract Factory Class for creating different types of events

class FundRaiserEventFactory extends EventFactory{
    
    // create an object of Fundraiser event and return it
    public function eventFactory($args)
    {
        // extract arguments from the list args
        // $title = null;
        // $address = null;
        // $dateTime = null;
        // $goalAMount = null;
        // $raisedAmount = null;
        // return new Fundraiser($title, $address, $dateTime, $goalAmount, $raisedAmount);  
    } 

}

?>
