<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Person.php";

class Attendee extends Person {
    private int $attendeeID;
    private array $tickets = [];

    public function __construct(int $id, string $name, string $date_of_birth, string $national_id, int $address_id, string $phone_number) {
        parent::__construct($id, $name, $date_of_birth, $national_id, $address_id, $phone_number);
        $this->attendeeID = $id;
        $this->tickets = [];  // Initialize the tickets array
    }

    public function getAttendeeID(): int {
        return $this->attendeeID;
    }

    // Save method for inserting or updating the attendee
    public function save(): bool {
        if ($this->attendeeID === 0) {
            // Insert new attendee into the database
            $sql = "INSERT INTO Attendee (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sssis", $this->name, $this->date_of_birth, $this->national_id, $this->address, $this->phone_number);

            if ($stmt->execute()) {
                $this->attendeeID = Database::getInstance()->insert_id;  // Set the attendee ID after insertion
                return true;
            }
        } else {
            // Update existing attendee
            $sql = "UPDATE Attendee SET name = ?, date_of_birth = ?, national_id = ?, address_id = ?, phone_number = ? WHERE id = ?";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sssisi", $this->name, $this->date_of_birth, $this->national_id, $this->address, $this->phone_number, $this->attendeeID);

            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    // Create a new attendee
    public static function create(string $name, string $date_of_birth, string $national_id, int $address_id, string $phone_number): Attendee {
        $attendee = new static(0, $name, $date_of_birth, $national_id, $address_id, $phone_number);
        $attendee->save();
        return $attendee;
    }

    // Update an existing attendee
    public function update(string $name, string $date_of_birth, string $national_id, int $address_id, string $phone_number): void {
        $this->name = $name;
        $this->date_of_birth = $date_of_birth;
        $this->national_id = $national_id;
        $this->address = $address_id;
        $this->phone_number = $phone_number;

        $this->save();
    }

    // Delete an attendee
    public function delete(): void {
        static::executeUpdate("DELETE FROM Attendee WHERE id = ?", "i", $this->attendeeID);
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

    // Fetch all attendees from the database
    public static function getAllAttendees(): array {
        $rows = static::fetchAll("SELECT * FROM Attendee");
        $attendees = [];
        foreach ($rows as $row) {
            $attendees[] = new static(
                $row['id'], 
                $row['name'], 
                $row['date_of_birth'], 
                $row['national_id'], 
                $row['address_id'], 
                $row['phone_number']
            );
        }
        return $attendees;
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
?>
