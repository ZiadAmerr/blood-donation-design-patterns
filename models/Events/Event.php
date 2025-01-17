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

    public function showEventDetails(): string {
        return sprintf(
            "Event ID: %s\nTitle: %s\nMax Attendees: %d\nDate and Time: %s\nAddress: %s\n",
            $this->eventID,
            $this->title,
            $this->maxAttendees,
            $this->dateTime->format('Y-m-d H:i:s'),
            $this->address->__toString()
        );
    }

    public function addVolunteer(Volunteer $volunteer): void {
        $this->volunteers[] = $volunteer;
    }

    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;
    }
    

    public function issueTicket(float $attendeeID): Ticket {
        $ticket = new Ticket(uniqid(), $attendeeID, $this->eventID);
        $this->addTicket($ticket);
        return $ticket;
    }

    public function createAttendeeIterator(): AttendeeIterator{
        return new AttendeeIterator($this->attendees);
    }

    public function getAttendees(): array {
        return $this->attendees;
    }

    public function loadAllTickets(): array {
        $sql = "SELECT * FROM Tickets WHERE event_id = ?";
        $ticketsData = self::fetchAll($sql, "i", $this->eventID);

        return $ticketsData; 
    }
}

?>
