<?php

class Workshop {
    private Volunteer $instructor;
    private string $topic;

    public function __construct(Volunteer $instructor, string $topic) {
        $this->instructor = $instructor;
        $this->topic = $topic;
    }

    public function getDetails(): string {
        return "Workshop: {$this->topic}, Instructor: {$this->instructor->getName()}";
    }
}

?>
