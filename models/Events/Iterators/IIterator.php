<?php

interface IIterator {
    public function hasNext(): bool;
    public function next();
    public function remove(): bool;
}
?>