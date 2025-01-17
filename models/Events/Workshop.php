<?php

class Workshop {
    private int $id;
    private Volunteer $instructor;
    private string $topic;

    public function __construct(Volunteer $instructor, string $topic) {
        $this->instructor = $instructor;
        $this->topic = $topic;
    }

    
}

?>
