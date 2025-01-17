<?php

require 'IIterator.php';

class AttendeeIterator implements IIterator
{
    private $attendees;
    private $position = 0;

    public function __construct($attendees)
    {
        $this->attendees = $attendees;
    }

    public function hasNext(): bool
    {
        return $this->position < count($this->attendees);
    }

    public function next(): ?Attendee
    {
        if ($this->hasNext()) {
            return $this->attendees[$this->position++];
        }
        return null;
    }

    public function remove(): bool
    {
        if ($this->position > 0 && $this->position <= count($this->attendees)) {
            array_splice($this->attendees, $this->position - 1, 1);
            $this->position--;
            return true;
        }
        return false;
    }
}

?>