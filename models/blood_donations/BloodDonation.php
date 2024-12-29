<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once __DIR__ . "/Donation.php";
require_once __DIR__ . "/BloodStock.php";
require_once __DIR__ . "/BloodType.php";
require_once __DIR__ . "/Donor.php";

/**
 * BloodDonation extends the Donation base class.
 * 
 * A BloodDonation has:
 *   - a Donor
 *   - numberOfLiters
 *   - bloodType
 * 
 * 'increaseBloodStock()' adds the donated blood to the singleton BloodStock 
 * if the types match.
 */
class BloodDonation extends Donation
{
    private Donor $donor;
    private int $numberOfLiters;
    private string $bloodType;

    /**
     * @param Donor      $donor
     * @param int        $numberOfLiters
     * @param string     $bloodType  (or BloodType $bloodType if using real Enums in PHP 8.1+)
     */
    public function __construct(Donor $donor, int $numberOfLiters, string $bloodType)
    {
        // If the parent Donation constructor needs something, handle that here
        // e.g., parent::__construct(...) if necessary

        $this->donor          = $donor;
        $this->numberOfLiters = $numberOfLiters;
        $this->bloodType      = $bloodType;
    }

    /**
     * Increases the blood stock (singleton) by the donated amount, if types match.
     */
    public function increaseBloodStock(): bool
    {
        try {
            // Obtain the Singleton instance
            $bloodStock = BloodStock::getInstance();

            // Check if the stock type matches the donation type
            if ($bloodStock->getBloodType() === $this->bloodType) {
                // Add the donated liters
                $bloodStock->addToStock($this->numberOfLiters);

                echo "BloodDonation: Successfully added {$this->numberOfLiters} liters of {$this->bloodType} to the stock.<br>";
                return true;
            } else {
                echo "BloodDonation mismatch: Stock is for {$bloodStock->getBloodType()}, "
                    . "but donor gave {$this->bloodType}.<br>";
                return false;
            }
        } catch (Exception $e) {
            echo "Error adding donation to stock: " . $e->getMessage();
            return false;
        }
    }
}
