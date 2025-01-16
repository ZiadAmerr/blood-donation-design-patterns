<?php

require_once 'Iterator.php';

class EventIterator implements Iterator {
    private array $events;
    private int $position = 0;

    public function __construct(array $events) {
        $this->events = $events;
    }

    public function hasNext(): bool {
        return $this->position < count($this->events);
    }

    public function next(): ?DonationComponent {
        if ($this->hasNext()) {
            return $this->events[$this->position++];
        }
        return null;
    }

    public function remove(): void {
        if ($this->position > 0) {
            array_splice($this->events, $this->position - 1, 1);
            $this->position--;
        }
    }
}

?>
