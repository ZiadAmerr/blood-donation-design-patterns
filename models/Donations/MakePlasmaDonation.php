<?php

require_once 'DonationFacade.php';
require_once 'BloodDonation.php';

class MakePlasmaDonation implements Command {

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
            return $receiver->donatePlasma($donation);
        }
        //$bloodDonation = new BloodDonation($donor, 1, new BloodTypeEnum()); // Example: 1 liter, new BloodTypeEnum instance
        return false;
    }
}
?>
