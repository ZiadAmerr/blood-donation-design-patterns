<?php
require_once "Event.php";
require_once "Address.php";

class OutreachEvent extends Event {  
    private int $audience; 
    private array $activities;
    private array $listOfOrganizations;

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, int $audience, array $activities = [], array $listOfOrganizations = []) {
        parent::__construct($id, $title, $address, $dateTime);
        $this->audience = $audience;
        $this->activities = $activities;
        $this->listOfOrganizations = $listOfOrganizations;
    }

    public static function create(array $data): OutreachEvent {
        $outreachEvent = new self(
            0,
            $data['title'],
            new Address($data['address_id']),
            new DateTime($data['dateTime']),
            $data['audience']
        );

        $sql = "INSERT INTO OutreachEvent (title, address_id, date_time, audience) VALUES (?, ?, ?, ?)";
        $outreachEventId = $outreachEvent->executeUpdate(
            $sql,
            "sisdi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['audience']
        );
        $outreachEvent->id = $outreachEventId;

        return $outreachEvent;
    }

    public function addActivity(string $activity): void {
        $this->activities[] = $activity;
    }

    public function inviteOrganization(string $organization): void {
        $this->listOfOrganizations[] = $organization;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->audience = $data['audience'];

        $sql = "UPDATE OutreachEvent SET title = ?, address_id = ?, date_time = ?, audience = ? WHERE id = ?";
        $this->executeUpdate(
            $sql,
            "sisdi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['audience'],
            $this->id
        );
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
            $this->activities = $row['activities'] ?? [];
            $this->listOfOrganizations = $row['organizations'] ?? [];
        }
    }

    protected function getDetails(): string {
        return "Outreach Event: {$this->title}, Audience: {$this->audience}, Activities: " . implode(", ", $this->activities);
    }
}
?>
