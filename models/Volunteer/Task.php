<?php

class Task {
    public int $taskID;
    public string $taskName;
    public string $taskDescription;
    public int $hoursRequired;
    public bool $isCompleted;

    public function __construct(int $taskID, string $taskName, string $taskDescription, int $hoursRequired, bool $isCompleted = false) {
        $this->taskID = $taskID;
        $this->taskName = $taskName;
        $this->taskDescription = $taskDescription;
        $this->hoursRequired = $hoursRequired;
        $this->isCompleted = $isCompleted;
    }

    // Mark the task as completed and update the database
    public function markAsCompleted(): void {
        $this->isCompleted = true;
        $this->update("Tasks", ["isCompleted" => true], "taskID = ?", [$this->taskID]);
    }

    // Get task details
    public function getDetails(): string {
        return "Task ID: {$this->taskID}, Name: {$this->taskName}, Description: {$this->taskDescription}, Hours Required: {$this->hoursRequired}, Completed: " . ($this->isCompleted ? "Yes" : "No");
    }

    // Add a task to the database
    public function add(): void {
        $this->add("Tasks", [
            "taskID" => $this->taskID,
            "taskName" => $this->taskName,
            "taskDescription" => $this->taskDescription,
            "hoursRequired" => $this->hoursRequired,
            "isCompleted" => $this->isCompleted
        ]);
    }

    // Update a task in the database
    public function update(): void {
        $this->update("Tasks", [
            "taskName" => $this->taskName,
            "taskDescription" => $this->taskDescription,
            "hoursRequired" => $this->hoursRequired,
            "isCompleted" => $this->isCompleted
        ], "taskID = ?", [$this->taskID]);
    }

    // Delete a task from the database
    public function delete(): void {
        $this->delete("Tasks", "taskID = ?", [$this->taskID]);
    }

    
}

?>