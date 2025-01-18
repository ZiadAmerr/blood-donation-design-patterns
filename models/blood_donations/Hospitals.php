<?php
// File: Hospitals.php

require_once __DIR__ . '/IBeneficiary.php';
require_once __DIR__ . '/BloodTypeEnum.php';
require_once __DIR__ . '/BloodStock.php';

class Hospitals implements IBeneficiary
{
    private string $name;
    private string $address;
    private array $ownedBloodAmounts; // Blood amounts owned by the hospital
    private array $ownedPlasmaAmounts; 
    private array $plasmaStockAmounts; 
    private array $bloodStockAmounts; 
    private BloodStock $bloodStock; // Shared instance of BloodStock

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
?>