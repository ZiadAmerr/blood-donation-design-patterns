<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";

class OutreachEvent extends Event
{
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

    public static function create(array $data): OutreachEvent
{
    $event = new self(
        0,
        $data['title'],
        $data['maxAttendees'],
        $data['address_id'],
        new DateTime($data['dateTime']),
        $data['activities'] ?? [],
        $data['listOfOrganizations'] ?? []
    );

    // Insert into Event table first
    $sqlEvent = "INSERT INTO Events (title, address_id, `datetime`, maxattendees) VALUES (?, ?, ?, ?)";
    $eventId = $event->executeUpdate(
        $sqlEvent,
        "sisi",
        $data['title'],
        $data['address_id'],
        $event->dateTime->format('Y-m-d H:i:s'),
        $data['maxAttendees']
    );

    $event->eventID = $eventId;

    // Insert into OutreachEvents table with foreign key
    $sqlOutreachEvent = "INSERT INTO OutreachEvents (event_id) VALUES (?)";
    $event->executeUpdate($sqlOutreachEvent, "i", $eventId);

    // Add activities and organizations to the database using the junction tables
    $event->saveActivitiesToDatabase();
    $event->saveOrganizationsToDatabase();

    return $event;
}

public function save(): void
{
    if ($this->eventID === 0) {
        // Insert new Event into Event table
        $sqlEvent = "INSERT INTO Events (title, address_id, `datetime`, maxattendees) VALUES (?, ?, ?, ?)";
        $this->eventID = $this->executeUpdate(
            $sqlEvent,
            "sisi",
            $this->title,
            $this->address,
            $this->dateTime->format('Y-m-d H:i:s'),
            $this->maxAttendees
        );

        // Insert into OutreachEvents table with foreign key
        $sqlOutreachEvent = "INSERT INTO OutreachEvents (event_id) VALUES (?)";
        $this->executeUpdate($sqlOutreachEvent, "i", $this->eventID);

        $this->saveActivitiesToDatabase();
        $this->saveOrganizationsToDatabase();
    } else {
        // Update existing Event record in Event table
        $sqlEvent = "UPDATE Events SET title = ?, address_id = ?, `datetime` = ?, maxattendees = ? WHERE id = ?";
        $this->executeUpdate(
            $sqlEvent,
            "sisii",
            $this->title,
            $this->address,
            $this->dateTime->format('Y-m-d H:i:s'),
            $this->maxAttendees,
            $this->eventID
        );

        // Update activities and organizations
        $this->updateActivitiesInDatabase();
        $this->updateOrganizationsInDatabase();
    }
}


    public static function loadById(int $eventID): ?OutreachEvent
    {
        $sql = "SELECT * FROM OutreachEvents WHERE id = ?";
        $row = self::fetchSingle($sql, "i", $eventID);

        if ($row) {
            $outreachEvent = new self(
                $eventID,
                $row['title'],
                $row['maxattendees'],
                $row['address_id'],
                new DateTime($row['datetime'])
            );

            $outreachEvent->activities = self::loadActivities($eventID);
            $outreachEvent->listOfOrganizations = self::loadOrganizations($eventID);

            return $outreachEvent;
        }

        return null;
    }

    private static function loadActivities(int $eventID): array
    {
        $sql = "SELECT a.name FROM Activities a 
                JOIN outreach_event_activities oe ON oe.activity_id = a.id 
                WHERE oe.outreach_event_id = ?";
        $activities = self::fetchAll($sql, "i", $eventID);

        return array_column($activities, 'name');
    }

    private static function loadOrganizations(int $eventID): array
    {
        $sql = "SELECT o.name FROM Organizations o 
                JOIN outreach_event_organizations eo ON eo.organization_id = o.id 
                WHERE eo.outreach_event_id = ?";
        $organizations = self::fetchAll($sql, "i", $eventID);

        return array_column($organizations, 'name');
    }

    public function addActivity(string $activity): void
    {
        $this->activities[] = $activity;
        $this->addActivityToDatabase($activity);
    }

    public function inviteOrganization(string $organization): void
    {
        $this->listOfOrganizations[] = $organization;
        $this->inviteOrganizationToDatabase($organization);
    }

    private function addActivityToDatabase(string $activity): void
    {
        // Insert activity into Activities table
        $sql = "INSERT INTO Activities (name) VALUES (?)";
        $activityId = $this->executeUpdate($sql, "s", $activity);

        // Insert into junction table
        $sql = "INSERT INTO outreach_event_activities (outreach_event_id, activity_id) VALUES (?, ?)";
        $this->executeUpdate($sql, "ii", $this->eventID, $activityId);
    }

    private function inviteOrganizationToDatabase(string $organization): void
    {
        // Insert organization into Organizations table
        $sql = "INSERT INTO Organizations (name) VALUES (?)";
        $organizationId = $this->executeUpdate($sql, "s", $organization);

        // Insert into junction table
        $sql = "INSERT INTO outreach_event_organizations (outreach_event_id, organization_id) VALUES (?, ?)";
        $this->executeUpdate($sql, "ii", $this->eventID, $organizationId);
    }


    private function saveActivitiesToDatabase(): void
    {
        foreach ($this->activities as $activity) {
            $this->addActivityToDatabase($activity);
        }
    }

    private function saveOrganizationsToDatabase(): void
    {
        foreach ($this->listOfOrganizations as $organization) {
            $this->inviteOrganizationToDatabase($organization);
        }
    }

    private function updateActivitiesInDatabase(): void
    {
        $sql = "DELETE FROM outreach_event_activities WHERE outreach_event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        $this->saveActivitiesToDatabase();
    }

    private function updateOrganizationsInDatabase(): void
    {
        $sql = "DELETE FROM outreach_event_organizations WHERE outreach_event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        $this->saveOrganizationsToDatabase();
    }

    public function delete(): void
    {
        $sql = "DELETE FROM OutreachEvents WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        $sql = "DELETE FROM outreach_event_activities WHERE outreach_event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);

        $sql = "DELETE FROM outreach_event_organizations WHERE outreach_event_id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);
    }

    public function getActivities(): array
    {
        return $this->activities;
    }

    public function getOrganizations(): array
    {
        return $this->listOfOrganizations;
    }
}
