<?php
require 'Event.php';
require 'IDonationComponent.php';
require 'Iterators/IIterableAttendee.php';
require 'Iterators/IIterableEvent.php';

class DonationCampaign implements IDonationComponent, IIterableAttendee, IIterableEvent {
    private $eventList = [];
    private $attendeeList = [];

    public function showEventDetails(): string {
        $details = '';
        $iterator = $this->createEventIterator();
        while ($iterator->hasNext()) {
            $event = $iterator->next();
            $details .= $event->showDetails() . "\n";
        }
        return $details;
    }
        

    public function showAttendeeDetails(): void {
        $iterator = $this->createAttendeeIterator();
        while ($iterator->hasNext()) {
            $attendee = $iterator->next();
            echo $attendee->showDetails();
        }
    }

    public function addEvent(DonationCampaign $event) {
        $this->eventList[] = $event;
    }

    public function removeEvent(DonationCampaign $event) {
        $key = array_search($event, $this->eventList);
        if ($key !== false) {
            unset($this->eventList[$key]);
        }
    }

    public function createEventIterator(): EventIterator{
        return new EventIterator($this->eventList);
    }

    public function createAttendeeIterator(): AttendeeIterator{
        return new AttendeeIterator($this->attendeeList);
    }
}