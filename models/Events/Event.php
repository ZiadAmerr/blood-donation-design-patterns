<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

abstract class Event extends Model {
    protected float $eventID;
    protected string $title;
    protected int $maxAttendees;
    protected DateTime $dateTime;
    protected Address $address;
    protected array $attendees = [];
    protected array $volunteers = [];
    protected array $tickets = [];

    public function __construct(
        float $eventID,
        string $title,
        int $maxAttendees,
        DateTime $dateTime,
        Address $address
    ) {
        $this->eventID = $eventID;
        $this->title = $title;
        $this->maxAttendees = $maxAttendees;
        $this->dateTime = $dateTime;
        $this->address = $address;
    }

    abstract public function getDetails(): string;

    public function addVolunteer(Volunteer $volunteer): void {
        $this->volunteers[] = $volunteer;
    }

    public function issueTicket(float $attendeeID): Ticket {
        $ticket = new Ticket(uniqid(), $attendeeID, $this->eventID);
        $this->tickets[] = $ticket;
        return $ticket;
    }

    public function getAttendees(): array {
        return $this->attendees;
    }
}

?>
