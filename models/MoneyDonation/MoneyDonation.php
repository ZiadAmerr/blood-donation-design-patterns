<?php
// File: MoneyDonation.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/IMoneyDonationMethod.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';
// If you have a "MoneyDonationDetails" class, require it here

class MoneyDonation extends Donation
{
    public Donor $donor;
    public IMoneyDonationMethod $moneyDonationMethod;

    // How much money is being donated
    public float $amount;

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

    public static function create(
        int $donor_id, 
        string $type,
        float $amount = 0,
        string $date = null,
    ): int
    {
        $sql = "INSERT INTO `moneydonation`(`amount`, `date`, `donor_id`) VALUES (?,?,?)";
    
        // Ensure $date is in 'YYYY-MM-DD' format
        $formattedDate = date('Y-m-d', strtotime($date));
    
        // Execute the query with proper data types: double (d), string (s), integer (i)
        $exec_update = self::executeUpdate($sql, 'dsss', $amount, $formattedDate, $donor_id) > 0;

        $id = parent::create($donor_id, $type);

        return $id;
    }
    
    public static function fetchAllMoneyDonations(): array
    {
        $sql = "SELECT p.name as person_name, p.national_id, md.amount, md.date, md.type
        FROM moneydonation md 
                JOIN persons p ON md.donor_id = p.national_id";
        return self::fetchAll($sql);
    }
}