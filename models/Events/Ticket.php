<?php

class Ticket extends Model {
    private int $ID;
    private int $attendeeID;
    private int $eventID;

    public function __construct(int $attendeeID, int $eventID, int $ID = 0) {
        $this->ID = $ID;
        $this->attendeeID = $attendeeID;
        $this->eventID = $eventID;
    }

    public function getTicketID(): int {
        return $this->ID;
    }

    public function getAttendeeID(): int {
        return $this->attendeeID;
    }

    public function getEventID(): int {
        return $this->eventID;
    }

    public function getDetails(): string {
        return "Ticket ID: {$this->ID}, Event ID: {$this->eventID}, Attendee ID: {$this->attendeeID}";
    }

    public static function create(int $attendeeID, int $eventID): Ticket {
        $ticketID = static::generateTicketID();
        return new static($attendeeID, $eventID, $ticketID);
    }

    // Simulate ticket ID generation 
    private static function generateTicketID(): int {
        return rand(1000, 9999);
    }

    // Save method for inserting or updating the ticket
    public function save(): bool {
        if ($this->ID === 0) {
            // Insert new ticket into the database
            $sql = "INSERT INTO Ticket (attendee_id, event_id) VALUES (?, ?)";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("ii", $this->attendeeID, $this->eventID);

            if ($stmt->execute()) {
                $this->ID = Database::getInstance()->insert_id;  // Set the ticket ID after insertion
                return true;
            }
        } else {
            // Update existing ticket
            $sql = "UPDATE Ticket SET attendee_id = ?, event_id = ? WHERE id = ?";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("iii", $this->attendeeID, $this->eventID, $this->ID);

            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }

    // Load a ticket by its ID using the fetchSingle method from the Model
    public static function loadByID(int $ticketID): Ticket {
        $sql = "SELECT * FROM Ticket WHERE id = ?";
        $ticketRow = static::fetchSingle($sql, "i", $ticketID);  // Calling fetchSingle statically
        
        if ($ticketRow) {
            return new static($ticketRow['attendee_id'], $ticketRow['event_id'], $ticketRow['id']);
        }

        throw new Exception("Ticket not found");
    }
}
?>
