<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/EventIterator.php";

interface IIterableEvent
{
    public function createEventIterator(): EventIterator;
}

?>