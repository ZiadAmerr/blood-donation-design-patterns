<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Donor.php';
require_once 'Certificate.php';
require_once 'Task.php';

/**
 * Class Volunteer
 * Extends Donor
 * 
 * - Has tasks (for volunteering events)
 * - Has total hours contributed
 * - Manages volunteer-specific logic, like skills, tasks, certificates
 */
class Volunteer extends Donor
{
    public bool $isAvailable = true;
    public array $skills = [];
    public int $totalHours = 0;
    public array $tasks = [];

    public function __construct(int $person_id)
    {
        // Call Donor constructor which calls Person constructor
        parent::__construct($person_id);

        // In your DB, you might store volunteer-specific fields in a separate table, e.g. Volunteer table
        // and join on the Donor or Person table. For now, let's assume it's all in Person or Donor tables.

        // Load volunteer data (like isAvailable) from a hypothetical table:
        // $row = $this->fetchSingle("SELECT is_available FROM VolunteerTable WHERE person_id = ?", "i", $person_id);
        // $this->isAvailable = $row ? (bool)$row['is_available'] : true;

        // Load skills from DB
        $this->loadSkills();

        // Load tasks from DB
        $this->loadTasks();
    }

    /**
     * Create a Volunteer in DB.
     * This could be a convenience method to create a Person + Donor + Volunteer all at once
     */
    public static function createVolunteer(
        string $name,
        string $dob,
        string $nationalId,
        int    $addressId,
        string $phone
    ): ?Volunteer {
        try {
            // 1) Insert into Person or Donor table
            //    Donor::create(...) already does something like that
            $donor_id = Donor::create($name, $dob, $nationalId, (string)$addressId, $phone);

            // 2) Possibly insert volunteer-specific row in a separate table
            //    e.g., "INSERT INTO VolunteerTable(person_id, is_available) VALUES (?, 1)"
            // static::executeUpdate(
            //     "INSERT INTO VolunteerTable (person_id, is_available) VALUES (?, ?)",
            //     "ii",
            //     $donor_id,
            //     1
            // );

            // 3) Return a new Volunteer object
            return new Volunteer($donor_id);
        } catch (\Exception $e) {
            error_log("Failed to create volunteer: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Update volunteer data. For instance, to toggle availability or update phone
     */
    public function updateVolunteer(string $name, string $phone, bool $isAvailable = true): void
    {
        // Update parent donor or person fields
        // Example: update phone in `Donor` table
        static::executeUpdate(
            "UPDATE Donor SET name = ?, phone_number = ? WHERE person_id = ?",
            "ssi",
            $name,
            $phone,
            $this->person_id
        );

        // Update volunteer-specific table if it exists
        // static::executeUpdate(
        //     "UPDATE VolunteerTable SET is_available = ? WHERE person_id = ?",
        //     "ii",
        //     $isAvailable ? 1 : 0,
        //     $this->person_id
        // );

        // Re-load fields
        $this->name = $name;
        $this->phone_number = $phone;
        $this->isAvailable = $isAvailable;
    }

    /**
     * Delete a volunteer
     */
    public function deleteVolunteer(): void
    {
        // If you have a separate volunteer table:
        static::executeUpdate(
            "DELETE FROM VolunteerTable WHERE person_id = ?",
            "i",
            $this->person_id
        );

        // Then you can call parent::delete() which may remove from Donor / Person

    }

    // ----------------------
    // Skills
    // ----------------------
    private function loadSkills(): void
    {
        $rows = $this->fetchAll(
            "SELECT skill FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );
        foreach ($rows as $row) {
            $this->skills[] = $row['skill'];
        }
    }

    public function addSkill(string $skill): void
    {
        // Insert into DB
        static::executeUpdate(
            "INSERT INTO VolunteerSkills (person_id, skill) VALUES (?, ?)",
            "is",
            $this->person_id,
            $skill
        );

        // Update in-memory array
        $this->skills[] = $skill;
    }

    public function removeSkill(string $skill): void
    {
        // Remove from DB
        static::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ? AND skill = ?",
            "is",
            $this->person_id,
            $skill
        );

        // Remove from memory
        $this->skills = array_filter($this->skills, fn($s) => $s !== $skill);
    }

    // ----------------------
    // Tasks
    // ----------------------
    private function loadTasks(): void
    {
        $rows = $this->fetchAll(
            "SELECT * FROM Tasks WHERE person_id = ?",
            "i",
            $this->person_id
        );

        foreach ($rows as $row) {
            $this->tasks[] = new Task(
                $row['taskID'],
                $row['taskName'],
                $row['taskDescription'],
                $row['hoursRequired'],
                (bool)$row['isCompleted']
            );
        }
    }

    public function addTask(Task $task): void
    {
        $this->tasks[] = $task;
    }

    public function completeTask(int $taskID): void
    {
        foreach ($this->tasks as $task) {
            if ($task->taskID === $taskID) {
                $task->markAsCompleted();
                $this->increaseHours($task->hoursRequired);
            }
        }
    }

    public function increaseHours(int $hours): void
    {
        $this->totalHours += $hours;
        // Potentially store total hours in DB if needed
        // e.g. in a volunteer table
    }

    // ----------------------
    // Certificates
    // ----------------------
    public function generateCertificates(string $eventName): Certificate
    {
        $issueDate = date("Y-m-d");
        return new Certificate($this->name, $eventName, $this->totalHours, $issueDate);
    }
}
