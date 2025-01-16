<?php

interface DonationComponent {
    public function organizeDonation(): void;
    public function showDetails(): void;
    public function createIterator(): Iterator;
}

?>
