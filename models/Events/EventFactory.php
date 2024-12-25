<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";

// Abstract Factory Class for creating different types of events

abstract class EventFactory {
    
    // create an event and return it
    public function createEvent($args): ?Event
    {
        $event = $this->eventFactory($args);

        if (!$event) {
            throw new Exception("Failed to create event.");
        }

        return $event;
    }
    // will be overridden in children factories and return a concrete event according to the caller
    abstract public function eventFactory($args): ?Event; 

}

?>
