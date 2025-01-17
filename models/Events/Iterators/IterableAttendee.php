<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/AttendeeIterator.php";

interface IIterableAttendee
{
    public function createAttendeeIterator(): AttendeeIterator;
}

?>