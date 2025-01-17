<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/IIterator.php";

class EventIterator implements IIterator
{
    private $events;
    private $position = 0;

    public function __construct($events)
    {
        $this->events = $events;
    }

    public function hasNext(): bool
    {
        return $this->position < count($this->events);
    }

    public function next(): ?Event
    {
        if ($this->hasNext()) {
            return $this->events[$this->position++];
        }
        return null;
    }

    public function remove(): bool
    {
        if ($this->position > 0 && $this->position <= count($this->events)) {
            array_splice($this->events, $this->position - 1, 1);
            $this->position--;
            return true;
        }
        return false;
    }
}

?>