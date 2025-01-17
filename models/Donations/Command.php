<?php

interface Command {
    public function execute(DonationFacade $receiver, Donor $donor, Donation $donation = null): bool;
}

?>
