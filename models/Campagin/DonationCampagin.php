<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'DonationComponent.php';
require_once 'EventIterator.php';

class DonationCampaign implements DonationComponent {
    private array $events = [];

    public function organizeDonation(): void {
        foreach ($this->events as $event) {
            $event->organizeDonation();
        }
    }

    public function showDetails(): void {
        foreach ($this->events as $event) {
            $event->showDetails();
        }
    }

    public function addEvent(DonationComponent $event): void {
        $this->events[] = $event;
    }

    public function removeEvent(DonationComponent $event): void {
        $this->events = array_filter($this->events, fn($e) => $e !== $event);
    }

    public function createIterator(): Iterator {
        return new EventIterator($this->events);
    }
}

?>
