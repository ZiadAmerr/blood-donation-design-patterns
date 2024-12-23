<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

class OutreachEvent extends Event {  
    private int $audience; 
    private array $activities; // create a class (preferably) or just treat it as string
    private array $listOfOrganizations; // create a class (preferably) or just treat it as string

    public function __construct($title, $address, $dateTime, $audience, $activites, $listOfOrganizations) {
        parent::__construct($title, $address, $dateTime);

        // Initialize additional properties specific to FundraiserEvent
        $this->audience = $audience;
        $this->activities = $activites; 
        $this->listOfOrganizations =$listOfOrganizations; 
    }

    public function addActivity($activity) // we probably need to make activities classes 
    {
        // add activity objects
    }

    public function inviteOrganizations($organization) // we probably need to make organizations classes 
    {
        // add organization objects
    }

    public function getDetails(): string 
    {
        // get details of the event
        return "";
    }
}
?>
