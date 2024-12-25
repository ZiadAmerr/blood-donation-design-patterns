<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once "event.php";

class OutreachEvent extends Event {  
    private int $audience; 
    private array $activities; // TODO: create classes for activites
    private array $listOfOrganizations; // TODO: create classes for organizations

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, int $audience, array $activities = [], array $listOfOrganizations = []) {
        parent::__construct($id, $title, $address, $dateTime);
        
        $this->audience = $audience;
        $this->activities = $activities;
        $this->listOfOrganizations = $listOfOrganizations;
    }

    public static function create(array $data): OutreachEvent {
        // Create OutreachEvent instance
        $outreachEvent = new self(0, $data['title'], new Address($data['address_id']), new DateTime($data['dateTime']), $data['audience'], $data['activities'], $data['listOfOrganizations']);
        
        // Insert into the database
        $sql = "INSERT INTO OutreachEvent (title, address_id, date_time, audience) VALUES (?, ?, ?, ?)";
        $outreachEventId = $outreachEvent->executeUpdate($sql, "sisdi", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['audience']);
        $outreachEvent->id = $outreachEventId;

        return $outreachEvent;
    }

    public function addActivity($activity) {
        $this->activities[] = $activity;
    }

    public function inviteOrganizations($organization) {
        $this->listOfOrganizations[] = $organization;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->audience = $data['audience'];
        $this->activities = $data['activities'];
        $this->listOfOrganizations = $data['listOfOrganizations'];

        $sql = "UPDATE OutreachEvent SET title = ?, address_id = ?, date_time = ?, audience = ? WHERE id = ?";
        $this->executeUpdate($sql, "sisdi", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['audience'], $this->id);
    }

    public function delete(): void {
        $sql = "DELETE FROM OutreachEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->id);
    }

    public function load(): void {
        $sql = "SELECT * FROM OutreachEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->id);

        if ($row) {
            $this->title = $row['title'];
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->audience = (int)$row['audience'];
        }
    }

    public function getDetails(): string {
        // Generate details for this event
        return "Outreach Event: {$this->title}, Audience: {$this->audience}, Activities: " . implode(", ", $this->activities);
    }
}
?>
