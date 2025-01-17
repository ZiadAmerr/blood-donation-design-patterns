<?php

require 'AttendeeIterator.php';

interface IIterableAttendee
{
    public function createAttendeeIterator(): AttendeeIterator;
}

?>