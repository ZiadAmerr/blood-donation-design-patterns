<?php
// File: BloodBank.php

require_once __DIR__ . '/IBeneficiary.php';
require_once __DIR__ . '/BloodTypeEnum.php';
require_once __DIR__ . '/BloodStock.php';

class BloodBank implements IBeneficiary
{
    private string $name;
    private string $address;
    private array $ownedBloodAmounts; 
    private array $ownedPlasmaAmounts; 
    private BloodStock $bloodStock; 

    public function __construct(string $name, string $address, BloodStock $bloodStock)
    {
        $this->name = $name;
        $this->address = $address;
        $this->ownedBloodAmounts = array_fill_keys(BloodTypeEnum::values(), 0.0); 
        $this->ownedPlasmaAmounts = array_fill_keys(BloodTypeEnum::values(), 0.0);
        $this->bloodStock = $bloodStock;

        // Register this hospital as an observer
        $this->bloodStock->addBeneficiary($this);
    }

    /**
     * Called by the BloodStock instance when stock is updated.
     */
    public function update( array $ownedBloodAmounts, array $ownedPlasmaAmounts): bool
    {
        $this->ownedBloodAmounts = $ownedBloodAmounts; 
        $this->ownedPlasmaAmounts = $ownedPlasmaAmounts;
        return true;
    }

    /**
     * Request blood from the centralized BloodStock.
     */
    public function requestBlood(BloodTypeEnum $bloodType, float $amount): bool
    {
        // Attempt to remove the requested blood from BloodStock
        if ($this->bloodStock->removeFromBloodStock($bloodType, $amount)) {
            // If successful, add the requested amount to ownedBloodAmounts
            $this->ownedBloodAmounts[$bloodType] += $amount;
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
            $this->ownedBloodAmounts[$bloodType] += $amount;
            return true;
        }

        // If removal failed, return false
        return false;
    }
}
?>