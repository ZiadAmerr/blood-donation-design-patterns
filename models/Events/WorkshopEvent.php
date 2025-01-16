<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "Event.php";
require_once "Address.php";

class WorkShopEvent extends Event {
    private string $instructor;
    private int $maxAttendees;
    private array $attendees;
    private array $materials;

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, string $instructor, int $maxAttendees, array $attendees = [], array $materials = []) {
        parent::__construct($id, $title, $address, $dateTime);

        $this->instructor = $instructor;
        $this->maxAttendees = $maxAttendees;
        $this->attendees = $attendees;
        $this->materials = $materials;
    }

    public static function create(array $data): WorkShopEvent {
        $workShopEvent = new self(
            0,
            $data['title'],
            new Address($data['address_id']),
            new DateTime($data['dateTime']),
            $data['instructor'],
            $data['maxAttendees']
        );

        $sql = "INSERT INTO WorkShopEvent (title, address_id, date_time, instructor, max_attendees) VALUES (?, ?, ?, ?, ?)";
        $workShopEventId = $workShopEvent->executeUpdate(
            $sql,
            "sisdi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['instructor'],
            $data['maxAttendees']
        );
        $workShopEvent->id = $workShopEventId;

        return $workShopEvent;
    }

    public function registerAttendee(string $attendee): void {
        if (count($this->attendees) < $this->maxAttendees) {
            $this->attendees[] = $attendee;
        } else {
            throw new Exception("Maximum attendees limit reached.");
        }
    }

    public function addMaterial(string $material): void {
        $this->materials[] = $material;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->instructor = $data['instructor'];
        $this->maxAttendees = $data['maxAttendees'];

        $sql = "UPDATE WorkShopEvent SET title = ?, address_id = ?, date_time = ?, instructor = ?, max_attendees = ? WHERE id = ?";
        $this->executeUpdate(
            $sql,
            "sisdis",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['instructor'],
            $data['maxAttendees'],
            $this->id
        );
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
            $this->attendees = $this->loadAttendees();
            $this->materials = $this->loadMaterials();
        }
    }

    private function loadAttendees(): array {
        $sql = "SELECT name FROM WorkshopAttendees WHERE event_id = ?";
        $rows = $this->fetchMultiple($sql, "i", $this->id);
        return array_column($rows, 'name');
    }

    private function loadMaterials(): array {
        $sql = "SELECT material FROM WorkshopMaterials WHERE event_id = ?";
        $rows = $this->fetchMultiple($sql, "i", $this->id);
        return array_column($rows, 'material');
    }

    public function getDetails(): string {
        return "Workshop Event: {$this->title}, Instructor: {$this->instructor}, Max Attendees: {$this->maxAttendees}, Materials: " . implode(", ", $this->materials);
    }
}
?>
