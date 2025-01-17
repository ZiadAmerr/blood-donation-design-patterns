<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Activity extends Model
{
    public int $id;
    public int $eventId;
    public string $name;
    public string $description;
    public string $location;

    public function __construct(int $id)
    {
        $data = self::fetchSingle("SELECT * FROM Activities WHERE id = ?", "i", $id);

        if (!$data) {
            throw new Exception("Activity with ID $id not found.");
        }

        $this->id = $data['id'];
        $this->eventId = $data['event_id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->location = $data['location'];
    }

    public static function create(int $eventId, string $name, string $description, string $location): Activity
    {
        self::validateEventId($eventId);

        $id = self::executeUpdate(
            "INSERT INTO Activities (event_id, name, description, location) VALUES (?, ?, ?, ?)",
            "isss",
            $eventId,
            $name,
            $description,
            $location
        );

        return new Activity($id);
    }

    public function update(string $name, string $description, string $location): void
    {
        self::executeUpdate(
            "UPDATE Activities SET name = ?, description = ?, location = ? WHERE id = ?",
            "sssi",
            $name,
            $description,
            $location,
            $this->id
        );

        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
    }

    // Static method to load all Activities for a given eventId
    public static function load(int $eventId): array
    {
        $data = self::fetchAll(
            "SELECT * FROM Activities WHERE event_id = ?",
            "i",
            $eventId
        );

        if (!$data) {
            throw new Exception("No activities found for event ID $eventId.");
        }

        $activities = [];
        foreach ($data as $activityData) {
            $activities[] = new Activity($activityData['id']);
        }

        return $activities;
    }


    public function delete(): void
    {
        self::executeUpdate(
            "DELETE FROM Activities WHERE id = ?",
            "i",
            $this->id
        );
    }

    private static function validateEventId(int $eventId): void
    {
        $exists = self::fetchSingle(
            "SELECT id FROM Events WHERE id = ?",
            "i",
            $eventId
        );

        if (!$exists) {
            throw new Exception("Event with ID $eventId does not exist.");
        }
    }

}

?>
