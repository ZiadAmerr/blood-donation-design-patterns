<?php

require 'EventIterator.php';

interface IIterableEvent
{
    public function createEventIterator(): EventIterator;
}

?>