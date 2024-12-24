<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";

class WorkShopEventFactory extends EventFactory{

    // returns an object of workshop event
    public function eventFactory($args): ?Event
    {
        // extract arguments from the list args
        // return new WorkShopEvent($title, $address, $dateTime, $instructor, $maxAttendees, $materials);
        return null;
    }

}

?>
