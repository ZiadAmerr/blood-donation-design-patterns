<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";

class FundraiserEvent extends Event {
    private float $goalAmount;
    private float $raisedAmount;

    public function __construct($title, $address, $dateTime, $goalAmount = 0) {
        parent::__construct($title, $address, $dateTime);

        // Initialize additional properties specific to FundraiserEvent
        $this->goalAmount = $goalAmount;
        $this->raisedAmount = 0;
    }

    public function updateRaisedAmount($amount): void 
    {
        // update amount and check if goal amount reached
    }

    public function getDetails(): string
    {
        // get details of the event
        return "";
    }
}
?>
