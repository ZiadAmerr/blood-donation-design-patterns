<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Attendee extends Person {
    private int $attendeeID;
    private array $tickets = [];

    public function __construct($id, $name, $date_of_birth, $national_id, $address_id, $phone_number) {
        parent::__construct($id, $name, $date_of_birth, $national_id, $address_id, $phone_number);
        
        $this->attendeeID = $id;
        $this->tickets = [];  
    }

    // Create a new attendee
    public static function create($name, $date_of_birth, $national_id, $address_id, $phone_number): Attendee {
        $id = static::executeUpdate(
            "INSERT INTO Attendee (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)",
            "sssis",
            $name, $date_of_birth, $national_id, $address_id, $phone_number
        );
        return new static($id, $name, $date_of_birth, $national_id, $address_id, $phone_number);
    }

    // Update an existing attendee
    public function update($name, $date_of_birth, $national_id, $address_id, $phone_number): void {
        static::executeUpdate(
            "UPDATE Attendee SET name = ?, date_of_birth = ?, national_id = ?, address_id = ?, phone_number = ? WHERE id = ?",
            "sssisi",
            $name, $date_of_birth, $national_id, $address_id, $this->id, $phone_number
        );
    }

    public function delete(): void {
        static::executeUpdate("DELETE FROM Attendee WHERE id = ?", "i", $this->attendeeID);
    }

    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;
    }

    public function getTickets(): array {
        return $this->tickets;
    }

    public function getAttendeeID(): int {
        return $this->attendeeID;
    }

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
    public function showDetails(): array {
        return [
            'attendeeID' => $this->attendeeID,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'national_id' => $this->national_id,
            'address_id' => $this->address,
            'phone_number' => $this->phone_number,
            'tickets' => array_map(function($ticket) {
                return $ticket->getDetails();
            }, $this->tickets)
        ];
    }
}
