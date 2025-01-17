<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";
require_once "Address.php";

class WorkShopEvent extends Event {

    protected array $workshops = [];

    public function __construct(
        float $eventID,
        string $title,
        int $maxAttendees,
        DateTime $dateTime,
        Address $address,
        array $workshops = []
    ) 
    {
        parent::__construct($eventID, $title, $maxAttendees, $dateTime, $address);
        $this->workshops = $workshops;
    }

    public static function create(array $data): WorkShopEvent {
        $workShopEvent = new self(
            0,
            $data['title'],
            $data['maxAttendees'],
            new DateTime($data['dateTime']),
            new Address($data['address_id']),
            $data['workshops'] ?? []
        );

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

        return $workShopEvent;
    }
    
    public function addWorkshop(string $workshop): void {
        $this->workshops[] = $workshop;
    }

    public function getWorkshops(): array {
        return $this->workshops;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->maxAttendees = $data['maxAttendees'];
        $this->workshops = $data['workshops'] ?? [];

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
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->maxAttendees = (int)$row['max_attendees'];
            $this->workshops = $this->loadWorkshops();
        }
    }

    private function loadWorkshops(): array {
        // Use the fetchAll method from the DatabaseTrait to fetch multiple records
        $sql = "SELECT name FROM Workshops WHERE event_id = ?";
        $workshops = $this->fetchAll($sql, "i", $this->eventID);
    
        // Extract the workshop names into an array
        $workshopNames = [];
        foreach ($workshops as $workshop) {
            $workshopNames[] = $workshop['name'];
        }
    
        return $workshopNames;
    }
    

    public function getDetails(): string {
        return parent::showEventDetails() . "Workshops: " . implode(", ", $this->workshops);
    }
}

?>
