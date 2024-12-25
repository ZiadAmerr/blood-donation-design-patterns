<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class MoneyDonation extends Donation {
    // Donation ID should be removed and generated by the database instead.
    public Donor $donor;
    private IMoneyDonationMethod $moneyDonationMethod;
    // private MoneyDonationDetails $moneyDonationDetails;

    public function __construct($donor, $moneyDonationMethod, $moneyDonationDetails) {
        $this->donor = $donor;
        $this->moneyDonationMethod = $moneyDonationMethod;
        // $this->moneyDonationDetails = $moneyDonationDetails;
    }

    public function getReceipt(): bool {
        // Will be implemented later..
        //TODO: IMPLEMENT THIS FUNCTION.
        return true;
    }
}