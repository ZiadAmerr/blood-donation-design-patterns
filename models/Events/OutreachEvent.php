<?php

require_once "Event.php";
require_once "Address.php";

class OutreachEvent extends Event {

    private array $activities = [];
    private array $listOfOrganizations = [];

    public function __construct(
        int $eventID,
        string $title,
        int $maxAttendees,
        Address $address,
        DateTime $dateTime,
        array $activities = [],
        array $listOfOrganizations = []
    ) {
        parent::__construct($eventID, $title, $maxAttendees, $dateTime, $address);
        $this->activities = $activities;
        $this->listOfOrganizations = $listOfOrganizations;
    }

    public static function create(array $data): OutreachEvent {
        $outreachEvent = new self(
            0,
            $data['title'],
            $data['maxAttendees'],
            new Address($data['address_id']),
            new DateTime($data['dateTime']),
            $data['activities'] ?? [],
            $data['listOfOrganizations'] ?? []
        );

        $sql = "INSERT INTO OutreachEvent (title, address_id, date_time, max_attendees) VALUES (?, ?, ?, ?)";
        $outreachEventId = $outreachEvent->executeUpdate(
            $sql,
            "sisi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['maxAttendees']
        );
        $outreachEvent->eventID = $outreachEventId;

        return $outreachEvent;
    }

    public function addActivity(string $activity): void {
        $this->activities[] = $activity;
    }

    public function inviteOrganization(string $organization): void {
        $this->listOfOrganizations[] = $organization;
    }

    public function getActivities(): array {
        return $this->activities;
    }

    public function getOrganizations(): array {
        return $this->listOfOrganizations;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->activities = $data['activities'] ?? [];
        $this->listOfOrganizations = $data['listOfOrganizations'] ?? [];

        $sql = "UPDATE OutreachEvent SET title = ?, address_id = ?, date_time = ?, max_attendees = ? WHERE id = ?";
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
        $sql = "DELETE FROM OutreachEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);
    }

    public function load(): void {
        $sql = "SELECT * FROM OutreachEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->eventID);

        if ($row) {
            $this->title = $row['title'];
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->maxAttendees = (int)$row['max_attendees'];
            $this->activities = $this->loadActivities();
            $this->listOfOrganizations = $this->loadOrganizations();
        }
    }

    private function loadActivities(): array {
        // Use the fetchAll method from the DatabaseTrait to fetch activities
        $sql = "SELECT name FROM Activities WHERE event_id = ?";
        $activities = $this->fetchAll($sql, "i", $this->eventID);
    
        // Extract the activity names into an array
        $activityNames = [];
        foreach ($activities as $activity) {
            $activityNames[] = $activity['name'];
        }
    
        return $activityNames;
    }
    
    private function loadOrganizations(): array {
        // Use the fetchAll method from the DatabaseTrait to fetch organizations
        $sql = "SELECT name FROM Organizations WHERE event_id = ?";
        $organizations = $this->fetchAll($sql, "i", $this->eventID);
    
        // Extract the organization names into an array
        $organizationNames = [];
        foreach ($organizations as $organization) {
            $organizationNames[] = $organization['name'];
        }
    
        return $organizationNames;
    }
    
}

?>
