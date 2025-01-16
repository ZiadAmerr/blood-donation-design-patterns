<?php
// File: Hospitals.php
require_once __DIR__ . '/Ibeneficiaries.php';
require_once __DIR__ . '/BloodStock.php';
require_once __DIR__ . '/BloodType.php';

class Hospitals implements IBeneficiaries
{
    public function __construct()
    {
        // Attach this object to the singleton BloodStock as an observer
        $bloodStock = BloodStock::getInstance();
        $bloodStock->addBeneficiary($this);
    }
    
    /**
     * Requests a certain amount of a specific BloodTypeEnum from the stock.
     */
    public function requestBlood(int $amount, BloodTypeEnum $bloodType)
    {
        try {
            // Retrieve the singleton instance of BloodStock
            $bloodStock = BloodStock::getInstance();

            // Compare the requested type with the stock's type
            if ($bloodStock->getBloodType() === $bloodType) {
                // Check if enough is available
                if ($bloodStock->getAmount() >= $amount) {
                    // Remove from stock (which also triggers observer updates)
                    $success = $bloodStock->removeFromStock($amount);

                    if ($success) {
                        echo "Hospital requested {$amount} liters of {$bloodType}.<br>";
                    } else {
                        echo "Error removing from stock.<br>";
                    }
                } else {
                    echo "Not enough {$bloodType} in stock. Available: "
                        . $bloodStock->getAmount() . " liters<br>";
                }
            } else {
                echo "This stock is for {$bloodStock->getBloodType()}, not for {$bloodType}.<br>";
            }
        } catch (Exception $e) {
            echo "Error requesting blood: " . $e->getMessage();
        }
    }

    /**
     * Called automatically when BloodStock->updateBloodStock() is invoked.
     */
    public function update()
    {
        echo "Hospital notified: BloodStock changed.<br>";

        // Optional: you might want to retrieve the updated amount
        $bloodStock = BloodStock::getInstance();
        echo "Current stock: " . $bloodStock->getAmount()
            . " liters of " . $bloodStock->getBloodType() . "<br>";
    }
}
