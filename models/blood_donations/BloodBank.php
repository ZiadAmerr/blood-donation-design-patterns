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

    private string $name;
    private string $address;
    private array $ownedBloodAmounts; // Blood amounts owned by the hospital
    private array $ownedPlasmaAmounts; 
    private array $plasmaStockAmounts; 
    private array $bloodStockAmounts; 
    private BloodStock $bloodStock; 

    public function __construct(string $name, string $address, BloodStock $bloodStock)
    {
        $this->name = $name;
        $this->address = $address;
        $this->ownedBloodAmounts = array_fill_keys(BloodTypeEnum::values(), 0.0); 
        $this->ownedPlasmaAmounts = array_fill_keys(BloodTypeEnum::values(), 0.0);
        $this->bloodStock = $bloodStock;
        $this->plasmaStockAmounts = $this->bloodStock->getAllPlasmaStocks();
        $this->bloodStockAmounts = $this->bloodStock->getAllBloodStocks();
        // Register this hospital as an observer
        $this->bloodStock->addBeneficiary($this);
    }


    /**
     * Called by the BloodStock instance when stock is updated.
     */
    public function update( array $BloodStockAmounts, array $PlasmaStockAmounts): bool
    {
        $this->bloodStockAmounts = $BloodStockAmounts; 
        $this->plasmaStockAmounts = $PlasmaStockAmounts;
        return true;
    }

    /**
     * requestBlood(int $amount, BloodTypeEnum $bloodType)
     * 
     * Check if the requested amount of a specific BloodTypeEnum is available in BloodStock.
     * If enough is available, remove from stock. Otherwise, inform the user there's insufficient stock.
     */
    public function requestBlood(int $amount, BloodTypeEnum $bloodType)
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
     * Request blood from the centralized BloodStock.
     */
    public function requestBlood(BloodTypeEnum $bloodType, float $amount): bool
    {
        // Attempt to remove the requested blood from BloodStock
        if ($this->bloodStock->removeFromBloodStock($bloodType, $amount)) {
            // If successful, add the requested amount to ownedBloodAmounts
            $this->ownedBloodAmounts[$bloodType->getAsValue()] += $amount;
            
            return true;
        }

        // If removal failed, return false
        return false;
    }

    public function requestPlasma(BloodTypeEnum $bloodType, float $amount): bool
    {
        // Attempt to remove the requested blood from BloodStock
        if ($this->bloodStock->removeFromPlasmaStock($bloodType, $amount)) {
            // If successful, add the requested amount to ownedBloodAmounts
            $this->ownedBloodAmounts[$bloodType->getAsValue()] += $amount;
            return true;
        }

        // If removal failed, return false
        return false;
    }

    public function getPlasmaStockAmounts(): array
    {
        return $this->plasmaStockAmounts;
    }

    // Getter for blood stock amounts
    public function getBloodStockAmounts(): array
    {
        return $this->bloodStockAmounts;
    }
}
