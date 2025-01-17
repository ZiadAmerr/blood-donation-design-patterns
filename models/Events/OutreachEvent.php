<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";


class OutreachEvent extends Event {

    private array $activities = [];
    private array $listOfOrganizations = [];

    public function __construct(
        int $eventID,
        string $title,
        int $maxAttendees,
        int $addressID,
        DateTime $dateTime,
        array $activities = [],
        array $listOfOrganizations = []
    ) {
        parent::__construct($eventID, $title, $maxAttendees, $dateTime, $addressID);
        $this->activities = $activities;
        $this->listOfOrganizations = $listOfOrganizations;
    }

    public static function create(array $data): OutreachEvent {
        $outreachEvent = new self(
            0, 
            $data['title'],
            $data['maxAttendees'],
            $data['address_id'],  
            new DateTime($data['dateTime']),
            $data['activities'] ?? [],
            $data['listOfOrganizations'] ?? []
        );

        // Insert into OutreachEvent table
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

        // Add activities and organizations to the database using the junction table
        foreach ($data['activities'] as $activity) {
            $outreachEvent->addActivityToDatabase($activity);
        }

        foreach ($data['listOfOrganizations'] as $organization) {
            $outreachEvent->inviteOrganizationToDatabase($organization);
        }

        return $outreachEvent;
    }

    // Static method to load an event by its ID
    public static function loadById(int $eventID): ?OutreachEvent {
        $sql = "SELECT * FROM OutreachEvent WHERE id = ?";
        $row = self::fetchSingle($sql, "i", $eventID);  // use self:: to call static method

        if ($row) {
            $outreachEvent = new self(
                $eventID,
                $row['title'],
                $row['max_attendees'],
                $row['address_id'],
                new DateTime($row['date_time'])
            );

            // Load activities and organizations
            $outreachEvent->activities = self::loadActivities($eventID);  // static call
            $outreachEvent->listOfOrganizations = self::loadOrganizations($eventID);  // static call

            return $outreachEvent;
        }

        return null;  // Return null if not found
    }

    // Static method to load activities for a specific event
    private static function loadActivities(int $eventID): array {
        $sql = "SELECT a.name FROM Activities a 
                JOIN outreach_event_activities oe ON oe.activity_id = a.id 
                WHERE oe.outreach_event_id = ?";
        $activities = self::fetchAll($sql, "i", $eventID);

        $activityNames = [];
        foreach ($activities as $activity) {
            $activityNames[] = $activity['name'];
        }

        return $activityNames;
    }

    // Static method to load organizations for a specific event
    private static function loadOrganizations(int $eventID): array {
        $sql = "SELECT name FROM Organizations WHERE event_id = ?";
        $organizations = self::fetchAll($sql, "i", $eventID);

        $organizationNames = [];
        foreach ($organizations as $organization) {
            $organizationNames[] = $organization['name'];
        }

        return $organizationNames;
    }

    public function addActivity(string $activity): void {
        $this->activities[] = $activity;
        $this->addActivityToDatabase($activity);
    }

    public function inviteOrganization(string $organization): void {
        $this->listOfOrganizations[] = $organization;
        $this->inviteOrganizationToDatabase($organization);
    }

    // Add activity to the database and the junction table
    private function addActivityToDatabase(string $activity): void {
        $activityId = $this->executeUpdate(
            "INSERT INTO Activities (event_id, name) VALUES (?, ?)",
            "is",
            $this->eventID,
            $activity
        );

        $this->executeUpdate(
            "INSERT INTO outreach_event_activities (outreach_event_id, activity_id) VALUES (?, ?)",
            "ii",
            $this->eventID,
            $activityId
        );
    }

    // Invite organization to the database
    private function inviteOrganizationToDatabase(string $organization): void {
        $this->executeUpdate(
            "INSERT INTO Organizations (event_id, name) VALUES (?, ?)",
            "is",
            $this->eventID,
            $organization
        );
    }

    public function getActivities(): array {
        return $this->activities;
    }

    public function getOrganizations(): array {
        return $this->listOfOrganizations;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->addressID = $data['address_id'];  
        $this->dateTime = new DateTime($data['dateTime']);
        $this->activities = $data['activities'] ?? [];
        $this->listOfOrganizations = $data['listOfOrganizations'] ?? [];

        // Update OutreachEvent in database
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

        $this->updateActivitiesInDatabase();
        $this->updateOrganizationsInDatabase();
    }

    private function updateActivitiesInDatabase(): void {
        $sql = "DELETE FROM outreach_event_activities WHERE outreach_event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        foreach ($this->activities as $activity) {
            $this->addActivityToDatabase($activity);
        }
    }

    private function updateOrganizationsInDatabase(): void {
        $sql = "DELETE FROM Organizations WHERE event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        foreach ($this->listOfOrganizations as $organization) {
            $this->inviteOrganizationToDatabase($organization);
        }
    }

    public function delete(): void {
        $sql = "DELETE FROM OutreachEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);
    }

    public function save(): void {
        if ($this->eventID === 0) {
            $sql = "INSERT INTO OutreachEvent (title, address_id, date_time, max_attendees) VALUES (?, ?, ?, ?)";
            $this->eventID = $this->executeUpdate(
                $sql,
                "sisi",
                $this->title,
                $this->addressID,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->maxAttendees
            );

            foreach ($this->activities as $activity) {
                $this->addActivityToDatabase($activity);
            }

            foreach ($this->listOfOrganizations as $organization) {
                $this->inviteOrganizationToDatabase($organization);
            }
        } else {
            $sql = "UPDATE OutreachEvent SET title = ?, address_id = ?, date_time = ?, max_attendees = ? WHERE id = ?";
            $this->executeUpdate(
                $sql,
                "sisii",
                $this->title,
                $this->addressID,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->maxAttendees,
                $this->eventID
            );

            $this->updateActivitiesInDatabase();
            $this->updateOrganizationsInDatabase();
        }
    }
}
?>
