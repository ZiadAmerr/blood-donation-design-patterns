<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Donor.php';
require_once 'Certificate.php';
require_once 'Task.php';

class Volunteer extends Donor
{
    public bool $isAvailable = true;
    public array $skills = [];
    public int $totalHours = 0;
    public array $tasks = [];

    public function __construct(int $person_id)
    {
        parent::__construct($person_id);
        $this->loadSkills();
        $this->loadTasks();
    }

    // Add a skill and save it to the database
    public function addSkill(string $skill): void
    {
        $this->skills[] = $skill;

        // Save skill to the database
        static::executeUpdate(
            "INSERT INTO VolunteerSkills (person_id, skill) VALUES (?, ?)",
            "is",
            $this->person_id,
            $skill
        );
    }

    // Increase total hours by a specific amount
    public function increaseHours(int $hours): void
    {
        $this->totalHours += $hours;
    }

    // Add a task to the volunteer
    public function addTask(Task $task): void
    {
        $this->tasks[] = $task;
    }

    // Complete a task by ID and update total hours
    public function completeTask(int $taskID): void
    {
        foreach ($this->tasks as $task) {
            if ($task->taskID === $taskID) {
                $task->markAsCompleted();
                $this->increaseHours($task->hoursRequired);
            }
        }
    }

    // Generate a certificate for the volunteer
    public function generateCertificates(string $eventName): Certificate
    {
        $issueDate = date("Y-m-d");
        return new Certificate($this->name, $eventName, $this->totalHours, $issueDate);
    }

    // Load skills from the database
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

    // Load tasks assigned to the volunteer
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
                $row['isCompleted']
            );
        }
    }

    // Load a volunteer by ID (person_id)
    public static function loadById(int $id): ?Volunteer {
        // Fetch volunteer record
        $sql = "SELECT * FROM Donors WHERE person_id = ?";
        $row = static::fetchSingle($sql, "i", $id);

        if ($row) {
            // Create a new Volunteer object using the fetched data
            $volunteer = new self($row['person_id']);
            $volunteer->name = $row['name'];  // Assuming name is a field in the Donors table

            // Optionally, you can load other details or perform additional setup if necessary
            return $volunteer;
        }

        return null;  // Return null if no volunteer was found
    }

    // Delete volunteer (skills, tasks, and donor record)
    public function delete(): void
    {
        // Delete all skills from the database
        static::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );

        // Delete all tasks from the database
        static::executeUpdate(
            "DELETE FROM Tasks WHERE person_id = ?",
            "i",
            $this->person_id
        );
        // Call parent delete to remove the Donor and Person records

    }
    public static function allVolunteers(): array
    {
        // Example approach: fetch all donors and treat them as volunteers
        $rows = self::fetchAll("SELECT person_id FROM Donor");
        $volunteers = [];

        foreach ($rows as $row) {
            try {
                // Create a Volunteer for each person_id
                $volunteers[] = new self($row['person_id']);
            } catch (\Exception $e) {
                // handle/log error if a record fails
            }
        }
        return $volunteers;
    }
}
?>
