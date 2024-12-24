<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";

// Abstract Factory Class for creating different types of events

abstract class EventFactory {
    
    // create an event and return it
    public function createEvent(): ?Event
    {
        // this should call event factory and store the returned object in a memory then return it.
        return null;
    }
    // will be overridden in children factories and return a concrete event according to the caller
    abstract public function eventFactory($args): ?Event; 

}

?>
