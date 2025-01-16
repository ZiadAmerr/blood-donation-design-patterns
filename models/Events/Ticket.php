<?php

class Ticket {
    private float $ID;
    private float $attendeeID;
    private float $eventID;

    public function __construct(float $ID, float $attendeeID, float $eventID) {
        $this->ID = $ID;
        $this->attendeeID = $attendeeID;
        $this->eventID = $eventID;
    }

    public function getDetails(): string {
        return "Ticket ID: {$this->ID}, Event ID: {$this->eventID}, Attendee ID: {$this->attendeeID}";
    }
}

?>
