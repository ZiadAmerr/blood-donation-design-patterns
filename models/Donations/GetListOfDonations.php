<?php

require_once 'DonationFacade.php';

class GetListOfDonations implements Command {

    protected $receiver;

    public function __construct(DonationFacade $receiver) {
        $this->receiver = $receiver;
    }

    public function execute(DonationFacade $receiver, Donor $donor): bool {
        $receiver->get_donor_donations($donor);
        return true;
    }
}

?>
