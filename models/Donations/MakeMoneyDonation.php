<?php

require_once 'DonationFacade.php';
require_once 'MoneyDonation.php';

class MakeMoneyDonation implements Command {

    protected $receiver;

    public function __construct(DonationFacade $receiver) {
        $this->receiver = $receiver;
    }

    public function execute(DonationFacade $receiver, Donor $donor): bool {
        $moneyDonationMethod = new MoneyDonationMethod();

        $moneyDonation = new MoneyDonation($donor, $moneyDonationMethod, null); 
        return $receiver->donateMoney($moneyDonation);
    }
}
?>
