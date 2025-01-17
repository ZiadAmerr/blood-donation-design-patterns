<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Donations/DonationFacade.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';

class MakeMoneyDonation implements Command {

    protected $receiver;

    public function __construct(DonationFacade $receiver) {
        $this->receiver = $receiver;
    }

    public function execute(DonationFacade $receiver, Donor $donor, Donation $donation = null): bool {
        if ($donation === null) {
            return false;
        }
        if ($donation instanceof BloodDonation) {
            /** @var MoneyDonation $donation */
            return $receiver->donateMoney($donation);
        }
        //$bloodDonation = new BloodDonation($donor, 1, new BloodTypeEnum()); // Example: 1 liter, new BloodTypeEnum instance
        return false;
        /*$moneyDonationMethod = new MoneyDonationMethod();

        $moneyDonation = new MoneyDonation($donor, $moneyDonationMethod, null); 
        return $receiver->donateMoney($moneyDonation);*/
    }
}
?>
