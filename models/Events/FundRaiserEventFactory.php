<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

class FundRaiserEventFactory extends EventFactory{
    
    // create an object of Fundraiser event and return it
    public function eventFactory($args)
    {
        // extract arguments from the list args
        // return new Fundraiser($title, $address, $dateTime, $goalAmount, $raisedAmount);  
    } 

}

?>
