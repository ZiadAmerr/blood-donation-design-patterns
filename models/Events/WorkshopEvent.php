<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";

class WorkShopEvent extends Event {

    protected array $workshops = [];

    public function __construct(
        int $eventID,
        string $title,
        int $maxAttendees,
        DateTime $dateTime,
        int $addressID,
        array $workshops = []
    ) {
        parent::__construct($eventID, $title, $maxAttendees, $dateTime, $addressID);
        $this->workshops = $workshops;
    }

    public static function create(array $data): WorkShopEvent {
        $workShopEvent = new self(
            0, // The event ID will be generated after insertion
            $data['title'],
            $data['maxattendees'],
            new DateTime($data['dateTime']),
            $data['address_id'],
            $data['workshops'] ?? []
        );

        // Insert shared event details into Event table
        $sqlEvent = "INSERT INTO events (title, address_id, `datetime`, maxattendees) VALUES (?, ?, ?, ?)";
        $eventId = $workShopEvent->executeUpdate(
            $sqlEvent,
            "sisi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['maxAttendees']
        );

        $workShopEvent->eventID = $eventId;

        // Insert specific details into WorkShopEvents table
        $sqlWorkShopEvent = "INSERT INTO workshopevents (event_id) VALUES (?)";
        $workShopEvent->executeUpdate($sqlWorkShopEvent, "i", $eventId);

        // Add workshops to the database
        foreach ($data['workshops'] as $workshop) {
            $workShopEvent->addWorkshopToDatabase($workshop);
        }

        return $workShopEvent;
    }

    public function addWorkshop(string $workshop): void {
        $this->workshops[] = $workshop;
        $this->addWorkshopToDatabase($workshop);
    }

    private function addWorkshopToDatabase(string $workshop): void {
        $sql = "INSERT INTO Workshops (event_id, name) VALUES (?, ?)";
        $this->executeUpdate($sql, "is", $this->eventID, $workshop);
    }

    public function save(): bool {
        if ($this->eventID === 0) {
            // Insert new shared event details
            $sqlEvent = "INSERT INTO events (title, address_id, `datetime`, maxattendees) VALUES (?, ?, ?, ?)";
            $this->eventID = $this->executeUpdate(
                $sqlEvent,
                "sisi",
                $this->title,
                $this->address,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->maxAttendees
            );

            // Insert into WorkShopEvents table
            $sqlWorkShopEvent = "INSERT INTO workshopevents (event_id) VALUES (?)";
            $this->executeUpdate($sqlWorkShopEvent, "i", $this->eventID);

            // Add workshops
            foreach ($this->workshops as $workshop) {
                $this->addWorkshopToDatabase($workshop);
            }
            return true;
        } else {
            // Update existing shared event details
            $sqlEvent = "UPDATE events SET title = ?, address_id = ?,  `datetime` = ?, maxattendees = ? WHERE id = ?";
            $this->executeUpdate(
                $sqlEvent,
                "sisii",
                $this->title,
                $this->address,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->maxAttendees,
                $this->eventID
            );

            // Update workshops
            $this->updateWorkshopsInDatabase();
            return true;
        }
    }

    private function updateWorkshopsInDatabase(): void {
        // Remove old workshops
        $sql = "DELETE FROM Workshops WHERE event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        // Add new workshops
        foreach ($this->workshops as $workshop) {
            $this->addWorkshopToDatabase($workshop);
        }
    }

    public static function loadById(int $id): ?WorkShopEvent {
        // Fetch shared event details
        $sql = "SELECT * FROM Event WHERE id = ?";
        $row = static::fetchSingle($sql, "i", $id);

        if ($row) {
            $workshopEvent = new self(
                $row['id'],
                $row['title'],
                $row['max_attendees'],
                new DateTime($row['date_time']),
                $row['address_id']
            );

            // Load workshops associated with the event
            $workshopEvent->workshops = $workshopEvent->loadWorkshops();

            return $workshopEvent;
        }

        return null; // No event found
    }

    public function loadWorkshops(): array {
        $sql = "SELECT name FROM Workshops WHERE event_id = ?";
        $rows = $this->fetchAll($sql, "i", $this->eventID);

        return array_column($rows, 'name'); // Extract workshop names
    }

    public function delete(): void {
        // Delete shared event details
        $sqlEvent = "DELETE FROM Event WHERE id = ?";
        $this->executeUpdate($sqlEvent, "i", $this->eventID);

        // Workshops will be deleted automatically due to foreign key constraints
    }

    public function getDetails(): string {
        return parent::showEventDetails() . " Workshops: " . implode(", ", $this->workshops);
    }
}

?>
