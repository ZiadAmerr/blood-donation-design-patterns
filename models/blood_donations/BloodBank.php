<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once __DIR__ . "/IBeneficiaries.php";
require_once __DIR__ . "/BloodStock.php";
require_once __DIR__ . "/BloodType.php";

/**
 * BloodBank implements IBeneficiaries, meaning:
 *   1) It can request blood from the BloodStock Singleton.
 *   2) It has an update() method, called by BloodStock when stock changes.
 */
class BloodBank implements IBeneficiaries
{
    public function __construct()
    {
        // Obtain the singleton instance and register this BloodBank as an observer.
        $bloodStock = BloodStock::getInstance();
        $bloodStock->addBeneficiary($this);
    }

    /**
     * requestBlood(int $amount, BloodType $bloodType)
     * 
     * Check if the requested amount of a specific BloodType is available in BloodStock.
     * If enough is available, remove from stock. Otherwise, inform the user there's insufficient stock.
     */
    public function requestBlood(int $amount, BloodType $bloodType)
    {
        try {
            $bloodStock = BloodStock::getInstance();

            // Compare the BloodStock's current type with what's requested
            if ($bloodStock->getBloodType() === $bloodType) {
                if ($bloodStock->getAmount() >= $amount) {
                    // Remove the requested amount
                    $success = $bloodStock->removeFromStock($amount);

                    if ($success) {
                        echo "BloodBank requested {$amount} liters of {$bloodType}.<br>";
                    } else {
                        echo "Error: Could not remove from stock. Possibly insufficient amount.<br>";
                    }
                } else {
                    echo "Not enough in stock for type {$bloodType}. "
                        . "Currently available: " . $bloodStock->getAmount() . " liters.<br>";
                }
            } else {
                echo "This BloodStock is for type {$bloodStock->getBloodType()}, "
                    . "not for {$bloodType}.<br>";
            }
        } catch (Exception $e) {
            echo "Error requesting blood: " . $e->getMessage();
        }
    }

    /**
     * update()
     * 
     * Called by BloodStock->updateBloodStock().
     * This lets the BloodBank know that the stock has changed.
     */
    public function update()
    {
        echo "BloodBank has been notified of a BloodStock update.<br>";

        // Optional: Inspect the updated stock
        $bloodStock = BloodStock::getInstance();
        echo "New stock amount: " . $bloodStock->getAmount()
            . " liters of " . $bloodStock->getBloodType() . "<br>";
    }
}
