<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";

class WorkShopEvent extends Event {
    private string $instructor;  // Could be an Instructor class, or just a string
    private int $maxAttendees;
    private array $attendees;
    private array $materials;  // Could be an array of Material objects, or just strings

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, string $instructor, int $maxAttendees, array $attendees = [], array $materials = []) {
        parent::__construct($id, $title, $address, $dateTime);
        
        $this->instructor = $instructor;
        $this->maxAttendees = $maxAttendees;
        $this->materials = $materials;
    }

    public static function create(array $data): WorkShopEvent {
        // Create WorkShopEvent instance
        $workShopEvent = new self(0, $data['title'], new Address($data['address_id']), new DateTime($data['dateTime']), $data['instructor'], $data['maxAttendees'], $data['materials']);
        
        // Insert into the database
        $sql = "INSERT INTO WorkShopEvent (title, address_id, date_time, instructor, max_attendees) VALUES (?, ?, ?, ?, ?)";
        $workShopEventId = $workShopEvent->executeUpdate($sql, "sisds", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['instructor'], $data['maxAttendees']);
        $workShopEvent->id = $workShopEventId;

        return $workShopEvent;
    }

    public function registerAttendee($attendee) {
        $this->attendees[] = $attendee;
    }

    public function addMaterials($materials) {
        $this->materials[] = $materials;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->instructor = $data['instructor'];
        $this->maxAttendees = $data['maxAttendees'];
        $this->materials = $data['materials'];

        $sql = "UPDATE WorkShopEvent SET title = ?, address_id = ?, date_time = ?, instructor = ?, max_attendees = ? WHERE id = ?";
        $this->executeUpdate($sql, "sisdis", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['instructor'], $data['maxAttendees'], $this->id);
    }

    public function delete(): void {
        $sql = "DELETE FROM WorkShopEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->id);
    }

    public function load(): void {
        $sql = "SELECT * FROM WorkShopEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->id);

        if ($row) {
            $this->title = $row['title'];
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->instructor = $row['instructor'];
            $this->maxAttendees = (int)$row['max_attendees'];
        }
    }

    public function getDetails(): string {
        return "Workshop Event: {$this->title}, Instructor: {$this->instructor}, Max Attendees: {$this->maxAttendees}";
    }
}
?>
