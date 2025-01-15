<?php
// File: MoneyDonation.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once __DIR__ . '/Donation.php';             // Base class
require_once __DIR__ . '/IMoneyDonationMethod.php'; // Interface
require_once __DIR__ . '/Donor.php';                // Donor model
// If you have a "MoneyDonationDetails" class, require it here

class MoneyDonation extends Donation
{
    public Donor $donor;
    private IMoneyDonationMethod $moneyDonationMethod;

    // How much money is being donated
    private float $amount;

    // Optionally: private $moneyDonationDetails;  // if you have extra detail

    /**
     * @param Donor                  $donor
     * @param IMoneyDonationMethod   $moneyDonationMethod
     * @param float                  $amount
     * @param mixed                  $moneyDonationDetails   // optional
     */
    public function __construct(
        Donor $donor,
        IMoneyDonationMethod $moneyDonationMethod,
        float $amount,
        $moneyDonationDetails = null
    ) {
        // If the parent Donation constructor needs something, handle that:
        // e.g., parent::__construct($someDonationId) or so

        $this->donor                = $donor;
        $this->moneyDonationMethod  = $moneyDonationMethod;
        $this->amount               = $amount;
        // $this->moneyDonationDetails = $moneyDonationDetails;
    }

    /**
     * Actually process the money donation, calling the method's donate() function.
     * Returns true on success, false otherwise.
     */
    public function processDonation(): bool
    {
        // e.g., record the donation in DB if needed:
        // Donation::create($this->donor->person_id, 'money');

        $success = $this->moneyDonationMethod->donate($this->amount);

        if ($success) {
            echo "MoneyDonation: Payment method processed {$this->amount} successfully.<br>";
        } else {
            echo "MoneyDonation: Payment method failed to process donation.<br>";
        }

        return $success;
    }

    /**
     * getReceipt()
     * 
     * Here you might generate or display a receipt, store it in DB, etc.
     */
    public function getReceipt(): bool
    {
        // Minimal example: just echo a message or return true
        echo "MoneyDonation: Generating receipt for {$this->amount}.<br>";
        return true;
    }
}
