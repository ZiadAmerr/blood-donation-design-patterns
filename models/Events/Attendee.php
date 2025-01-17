<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Person.php";

class Attendee extends Person {
    private int $attendeeID;
    private array $tickets = [];

    public function __construct(
        int $id,
        string $name,
        string $date_of_birth,
        string $national_id,
        int $address_id,
        string $phone_number
    ) {
        parent::__construct($id, $name, $date_of_birth, $national_id, $address_id, $phone_number);
        $this->attendeeID = $id;
        $this->tickets = [];
    }

    public function getAttendeeID(): int {
        return $this->attendeeID;
    }

    // Save method for inserting or updating the attendee
    public function save(): bool {
        if ($this->attendeeID === 0) {
            // Insert new attendee into the database
            $sql = "INSERT INTO Attendee (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)";
            $this->attendeeID = static::executeUpdate(
                $sql,
                "sssis",
                $this->name,
                $this->date_of_birth,
                $this->national_id,
                $this->address,
                $this->phone_number
            );
            return true;
        } else {
            // Update existing attendee
            $sql = "UPDATE Attendee SET name = ?, date_of_birth = ?, national_id = ?, address_id = ?, phone_number = ? WHERE id = ?";
            static::executeUpdate(
                $sql,
                "sssisi",
                $this->name,
                $this->date_of_birth,
                $this->national_id,
                $this->address,
                $this->phone_number,
                $this->attendeeID
            );
            return true;
        }
    }

    // Create a new attendee (overrides Person's create method)
    public static function create(
        string $name,
        string $date_of_birth,
        string $phone_number,
        string $national_id,
        string $username,
        string $password,
        int $address_id
    ): int {
        $id = static::executeUpdate(
            "INSERT INTO Person (name, date_of_birth, phone_number, national_id, username, password, address_id) VALUES (?, ?, ?, ?, ?, ?, ?)",
            "ssssssi",
            $name,
            $date_of_birth,
            $phone_number,
            $national_id,
            $username,
            $password,
            $address_id
        );
        return $id;
    }

    // Show details of the attendee along with their tickets
    public function showDetails(): array {
        return [
            'attendeeID' => $this->attendeeID,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'national_id' => $this->national_id,
            'address_id' => $this->address,  // Assuming the address property exists in the parent class
            'phone_number' => $this->phone_number,
            'tickets' => array_map(function($ticket) {
                return $ticket->getDetails();  // Assuming Ticket class has getDetails method
            }, $this->tickets)
        ];
    }

    // Add a ticket to the attendee
    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;

        // Insert into the junction table to associate ticket with attendee
        static::executeUpdate(
            "INSERT INTO attendee_tickets (attendee_id, ticket_id) VALUES (?, ?)",
            "ii",
            $this->attendeeID,
            $ticket->getTicketID()
        );
    }

    // Get all tickets associated with the attendee
    public function getTickets(): array {
        return $this->tickets;
    }

    // Load tickets from the database for this attendee
    public function loadTickets(): void {
        $sql = "SELECT ticket_id FROM attendee_tickets WHERE attendee_id = ?";
        $ticketRows = static::fetchAll($sql, "i", $this->attendeeID);

        foreach ($ticketRows as $ticketRow) {
            // Load the ticket using its ID
            $ticket = Ticket::loadByID($ticketRow['ticket_id']);
            $this->tickets[] = $ticket;
        }
    }
}