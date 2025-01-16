<?php

class Attendee {
    private int $attendeeID;
    private array $tickets = [];

    public function __construct(int $attendeeID) {
        $this->attendeeID = $attendeeID;
    }

    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;
    }

    public function getTickets(): array {
        return $this->tickets;
    }
}

?>
