<?php

class Task {
    public int $taskID;
    public string $taskName;
    public string $taskDescription;
    public int $hoursRequired;
    public bool $isCompleted;

    public function __construct(int $taskID, string $taskName, string $taskDescription, int $hoursRequired) {
        $this->taskID = $taskID;
        $this->taskName = $taskName;
        $this->taskDescription = $taskDescription;
        $this->hoursRequired = $hoursRequired;
        $this->isCompleted = false;
    }

    public function markAsCompleted(): void {
        $this->isCompleted = true;
    }

    public function getDetails(): string {
        return "Task ID: {$this->taskID}, Name: {$this->taskName}, Description: {$this->taskDescription}, Hours Required: {$this->hoursRequired}, Completed: " . ($this->isCompleted ? "Yes" : "No");
    }
}

?>