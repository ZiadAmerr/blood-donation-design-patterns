<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

class WorkShopEvent extends Event {
    private $instructor;
    private $maxAttendees;
    private $materials;

    public function __construct($title, $address, $dateTime, $instructor, $maxAttendees, $materials) {
        parent::__construct($title, $address, $dateTime);

        // Initialize additional properties specific to FundraiserEvent
        $this->instructor = $instructor;
        $this->maxAttendees = $maxAttendees; 
        $this->materials = $materials; 
    }

    public function registerAttendee($attendee) // we probably need to make attendee classes 
    {
        // add attendee objects
    }

    public function addMaterials($materials) // materials classes (or just strings?? idk)
    {
        // add material objects/strings ???
    }

    public function getDetails() 
    {
        // get details of the event
    }
}
?>
