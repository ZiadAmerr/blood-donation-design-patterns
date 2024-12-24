<?php

interface Command {
    public function execute(DonationFacade $receiver, Donor $donor): bool;
}

?>
