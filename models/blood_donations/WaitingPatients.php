<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once __DIR__ . "/IBeneficiaries.php";  // Adjust path if needed
require_once __DIR__ . "/BloodStock.php";       // Singleton
require_once __DIR__ . "/BloodType.php";        // Class or enum for blood types

class WaitingPatients implements IBeneficiaries
{
    public function __construct()
    {
        // Get the singleton instance of BloodStock
        $blood_bank = BloodStock::getInstance();

        // Register this WaitingPatients object as a beneficiary of the BloodStock instance
        $blood_bank->addBeneficiary($this);
    }

    /**
     * requestBlood(int $amount, BloodType $bloodType)
     *
     * Checks if the requested amount of a specific BloodType is available.
     * If enough is available, remove from stock. Otherwise, notify there's not enough.
     */
    public function requestBlood(int $amount, BloodTypeEnum $bloodType)
    {
        try {
            $bloodStock = BloodStock::getInstance();

            // Compare the stored type with what's requested
            if ($bloodStock->getBloodType() === $bloodType) {
                // Check if there's enough stock
                if ($bloodStock->getAmount() >= $amount) {
                    $success = $bloodStock->removeFromStock($amount);

                    if ($success) {
                        echo "WaitingPatients: Successfully allocated {$amount} liters of {$bloodType} to patients.<br>";
                    } else {
                        echo "WaitingPatients: Failed to remove blood from stock, possibly insufficient amount.<br>";
                    }
                } else {
                    echo "WaitingPatients: Not enough {$bloodType} in stock. Available: "
                        . $bloodStock->getAmount() . " liters.<br>";
                }
            } else {
                echo "WaitingPatients: This BloodStock is for {$bloodStock->getBloodType()}, not for {$bloodType}.<br>";
            }
        } catch (Exception $e) {
            echo "WaitingPatients: Error requesting blood: " . $e->getMessage();
        }
    }

    /**
     * update()
     *
     * Called automatically when BloodStock->updateBloodStock() is invoked.
     * This means the stock has changed (either increased or decreased).
     */
    public function update()
    {
        echo "WaitingPatients: Notified of BloodStock update.<br>";

        // Optionally, retrieve the new amount
        $bloodStock = BloodStock::getInstance();
        echo "WaitingPatients: Current stock is now "
            . $bloodStock->getAmount() . " liters of "
            . $bloodStock->getBloodType() . ".<br>";
    }
}
?>
