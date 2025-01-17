<?php
// File: BloodBank.php

require_once __DIR__ . '/IBeneficiary.php';
require_once __DIR__ . '/BloodTypeEnum.php';
require_once __DIR__ . '/BloodStock.php';

class BloodBank implements IBeneficiary
{
    private string $name;
    private string $address;
    private array $ownedBloodAmounts; // Blood amounts owned by the hospital
    private BloodStock $bloodStock; // Shared instance of BloodStock

    public function __construct(string $name, string $address, BloodStock $bloodStock)
    {
        $this->name = $name;
        $this->address = $address;
        $this->ownedBloodAmounts = array_fill_keys(BloodTypeEnum::values(), 0.0); // Initialize owned amounts
        $this->bloodStock = $bloodStock;

        // Register this hospital as an observer
        $this->bloodStock->addBeneficiary($this);
    }

    /**
     * Called by the BloodStock instance when stock is updated.
     */
    public function update(BloodTypeEnum $bloodType, float $amount): bool
    {
        // Hospital is notified about the stock update (without echoing)
        return true;
    }

    /**
     * Request blood from the centralized BloodStock.
     */
    public function requestBlood(BloodTypeEnum $bloodType, float $amount): bool
    {
        // Attempt to remove the requested blood from BloodStock
        if ($this->bloodStock->removeFromStock($bloodType, $amount)) {
            // If successful, add the requested amount to ownedBloodAmounts
            $this->ownedBloodAmounts[$bloodType] += $amount;
            return true;
        }

        // If removal failed, return false
        return false;
    }
}
?>
