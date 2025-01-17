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
        int $addressID,  // Changed to accept address_id instead of Address object
        array $workshops = []
    ) {
        parent::__construct($eventID, $title, $maxAttendees, $dateTime, $addressID);
        $this->workshops = $workshops;
    }

    public static function create(array $data): WorkShopEvent {
        $workShopEvent = new self(
            0, // The event ID will be generated after insertion
            $data['title'],
            $data['maxAttendees'],
            new DateTime($data['dateTime']),
            $data['address_id'], // Directly passing address_id
            $data['workshops'] ?? []
        );

        // Insert into WorkShopEvent table
        $sql = "INSERT INTO WorkShopEvent (title, address_id, date_time, max_attendees) VALUES (?, ?, ?, ?)";
        $workShopEventId = $workShopEvent->executeUpdate(
            $sql,
            "sisi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['maxAttendees']
        );
        
        $workShopEvent->eventID = $workShopEventId;

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

    // Add workshop to the database
    private function addWorkshopToDatabase(string $workshop): void {
        $sql = "INSERT INTO Workshops (event_id, name) VALUES (?, ?)";
        $this->executeUpdate(
            $sql,
            "is",
            $this->eventID,
            $workshop
        );
    }

    public function getWorkshops(): array {
        return $this->workshops;
    }

    // Save method for inserting or updating the workshop event
    public function save(): bool {
        if ($this->eventID === 0) {
            // Insert new workshop event into the database
            $sql = "INSERT INTO WorkShopEvent (title, address_id, date_time, max_attendees) VALUES (?, ?, ?, ?)";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sisi", $this->title, $this->addressID, $this->dateTime->format('Y-m-d H:i:s'), $this->maxAttendees);

            if ($stmt->execute()) {
                $this->eventID = Database::getInstance()->insert_id;  // Set the event ID after insertion
                // Add workshops
                foreach ($this->workshops as $workshop) {
                    $this->addWorkshopToDatabase($workshop);
                }
                return true;
            }
        } else {
            // Update existing workshop event
            $sql = "UPDATE WorkShopEvent SET title = ?, address_id = ?, date_time = ?, max_attendees = ? WHERE id = ?";
            $stmt = Database::getInstance()->prepare($sql);
            $stmt->bind_param("sisii", $this->title, $this->addressID, $this->dateTime->format('Y-m-d H:i:s'), $this->maxAttendees, $this->eventID);

            if ($stmt->execute()) {
                // Update workshops
                $this->updateWorkshopsInDatabase();
                return true;
            }
        }
        return false;
    }

    // Update workshops in the database
    private function updateWorkshopsInDatabase(): void {
        // Clear old workshops
        $sql = "DELETE FROM Workshops WHERE event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        // Add new workshops
        foreach ($this->workshops as $workshop) {
            $this->addWorkshopToDatabase($workshop);
        }
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->addressID = $data['address_id'];  // Changed to address_id
        $this->dateTime = new DateTime($data['dateTime']);
        $this->maxAttendees = $data['maxAttendees'];
        $this->workshops = $data['workshops'] ?? [];

        // Update WorkShopEvent in database
        $sql = "UPDATE WorkShopEvent SET title = ?, address_id = ?, date_time = ?, max_attendees = ? WHERE id = ?";
        $this->executeUpdate(
            $sql,
            "sisii",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['maxAttendees'],
            $this->eventID
        );
    }

    public function delete(): void {
        $sql = "DELETE FROM WorkShopEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);
    }

    public function load(): void {
        $sql = "SELECT * FROM WorkShopEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->eventID);

        if ($row) {
            $this->title = $row['title'];
            $this->addressID = $row['address_id'];
            $this->dateTime = new DateTime($row['date_time']);
            $this->maxAttendees = (int)$row['max_attendees'];
            $this->workshops = $this->loadWorkshops();
        }
    }

    private function loadWorkshops(): array {
        $sql = "SELECT name FROM Workshops WHERE event_id = ?";
        $workshops = $this->fetchAll($sql, "i", $this->eventID);
    
        // Extract the workshop names into an array
        $workshopNames = [];
        foreach ($workshops as $workshop) {
            $workshopNames[] = $workshop['name'];
        }
    
        return $workshopNames;
    }

    // Load a workshop event by ID (eventID)
    public static function loadById(int $id): ?WorkShopEvent {
        // Fetch event record
        $sql = "SELECT * FROM WorkShopEvent WHERE id = ?";
        $row = static::fetchSingle($sql, "i", $id);

        if ($row) {
            // Create a new WorkShopEvent object using the fetched data
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

        return null;  // Return null if no event was found
    }

    public function getDetails(): string {
        return parent::showEventDetails() . "Workshops: " . implode(", ", $this->workshops);
    }
}

?>
