<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Donor.php';
require_once 'Certificate.php';
require_once 'Task.php';

class Volunteer extends Donor implements IVolunteer {
    public bool $isAvailable = true; // Added to align with the diagram
    public array $skills = [];
    public array $tasks = [];
    public int $totalHours = 0; // Track total hours from completed tasks

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->loadSkills();
        $this->loadTasks();
    }

    // Add a skill and save it to the database
    public function addSkill(string $skill): void {
        $this->skills[] = $skill;

        // Save skill to the database
        static::executeUpdate(
            "INSERT INTO VolunteerSkills (person_id, skill) VALUES (?, ?)",
            "is",
            $this->person_id,
            $skill
        );
    }

    // Load skills from the database
    private function loadSkills(): void {
        $rows = $this->fetchAll(
            "SELECT skill FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );

        foreach ($rows as $row) {
            $this->skills[] = $row['skill'];
        }
    }

    // Add a task to the volunteer
    public function addTask(Task $task): void {
        $this->tasks[] = $task;
    }

    // Complete a task by ID and update total hours
    public function completeTask(int $taskID): void {
        foreach ($this->tasks as $task) {
            if ($task->taskID === $taskID) {
                $task->markAsCompleted();
                $this->totalHours += $task->hoursRequired;
            }
        }
    }

    // Generate a certificate for the volunteer
    public function generateCertificate(string $eventName): Certificate {
        $issueDate = date("Y-m-d");
        return new Certificate(0, $this->name, $eventName, $this->totalHours, $issueDate);
    }

    // Remove a skill from the database
    public function removeSkill(string $skill): void {
        $this->skills = array_filter($this->skills, fn($s) => $s !== $skill);

        // Remove skill from the database
        static::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ? AND skill = ?",
            "is",
            $this->person_id,
            $skill
        );
    }

    // Load tasks assigned to the volunteer
    private function loadTasks(): void {
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

    // Delete volunteer (skills, tasks, and donor record)
    public function delete(): void {
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
        parent::delete();
    }
}
