<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationFacade.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodDonation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/Command.php';

class MakeBloodDonation implements Command {

    protected $receiver;

    public function __construct(DonationFacade $receiver) {
        $this->receiver = $receiver;
    }

    public function execute(DonationFacade $receiver, Donor $donor, Donation $donation = null): bool {
        if ($donation === null) {
            return false;
        }
        if ($donation instanceof BloodDonation) {
            /** @var BloodDonation $donation */
            return $receiver->donateBlood($donation);
        }
        //$bloodDonation = new BloodDonation($donor, 1, new BloodTypeEnum()); // Example: 1 liter, new BloodTypeEnum instance
        return false;
    }
}
?>
