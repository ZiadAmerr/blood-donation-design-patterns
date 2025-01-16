<?php

require_once 'DonationFacade.php';
require_once 'BloodDonation.php';

class MakeBloodDonation implements Command {

    protected $receiver;

    public function __construct(DonationFacade $receiver) {
        $this->receiver = $receiver;
    }

    public function execute(DonationFacade $receiver, Donor $donor): bool {
        $bloodDonation = new BloodDonation($donor, 1, new BloodTypeEnum()); // Example: 1 liter, new BloodTypeEnum instance
        return $receiver->donateBlood($bloodDonation);
    }
}
?>
