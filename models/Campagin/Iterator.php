<?php

interface Iterator {
    public function hasNext(): bool;
    public function next(): ?DonationComponent;
    public function remove(): void;
}

?>
