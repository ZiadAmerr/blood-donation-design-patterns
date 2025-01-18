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

    // Save method for inserting or updating the activity
    public function save(): bool
    {
        if ($this->id === 0) {
            // Insert new activity into the database
            self::validateEventId($this->eventId);

            $id = self::executeUpdate(
                "INSERT INTO Activities (event_id, name, description, location) VALUES (?, ?, ?, ?)",
                "isss",
                $this->eventId,
                $this->name,
                $this->description,
                $this->location
            );

            $this->id = $id;  // Set the ID after insertion
            return true;
        } else {
            // Update existing activity
            self::executeUpdate(
                "UPDATE Activities SET name = ?, description = ?, location = ? WHERE id = ?",
                "sssi",
                $this->name,
                $this->description,
                $this->location,
                $this->id
            );

            return true;
        }
    }

    // Create a new activity
    public static function create(int $eventId, string $name, string $description, string $location): Activity
    {
        $activity = new static(0);  // Set ID to 0 for new activity
        $activity->eventId = $eventId;
        $activity->name = $name;
        $activity->description = $description;
        $activity->location = $location;
        $activity->save();

        return $activity;
    }

    // Update an existing activity
    public function update(string $name, string $description, string $location): void
    {
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;

        $this->save();
    }

    // Static method to load all activities for a given eventId
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
