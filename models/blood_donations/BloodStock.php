<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/IBloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/IBeneficiary.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodDonation.php";


class BloodStock extends Model implements IBloodStock
{
    private static ?BloodStock $instance = null;
    private array $listOfBeneficiaries = [];
    private array $bloodAmounts;
    private array $plasmaAmounts; // Added for plasma amounts

    /**
     * Private constructor ensures only one instance can be created.
     */
    private function __construct()
    {
        // Initialize the blood and plasma amounts map with 0 liters for each blood type and plasma
        $this->bloodAmounts = array_fill_keys(BloodTypeEnum::getAllValues(), 0.0);
        $this->plasmaAmounts = array_fill_keys(BloodTypeEnum::getAllValues(), 0.0); // Plasma initialization

        // Optionally fetch data from the database to populate initial stock for blood and plasma
        $rows = $this->fetchAll("SELECT blood_type, amount, is_plasma FROM BloodStock");
        foreach ($rows as $row) {
            if ($row['is_plasma'] == 1) {
                $this->plasmaAmounts[$row['blood_type']] = (float) $row['amount'];
            } else {
                $this->bloodAmounts[$row['blood_type']] = (float) $row['amount'];
            }
        }
    }

    /**
     * Singleton accessor
     */
    public static function getInstance(): BloodStock
    {
        if (self::$instance === null) {
            self::$instance = new BloodStock();
        }
        return self::$instance;
    }

    /**
     * Add blood to the stock for a specific blood type.
     */
    public function addToBloodStock( BloodTypeEnum $bloodType, float $amountToAdd): void
    {
        $this->bloodAmounts[$bloodType->value] += $amountToAdd;

        // Update the database
        static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount, is_plasma) VALUES (?, ?, 0) 
             ON DUPLICATE KEY UPDATE amount = amount + ?",
            "sdd",
            $bloodType->value,
            $amountToAdd,
            $amountToAdd
        );

        $this->notifyBeneficiaries(DonationType::BLOOD, $bloodType, $this->bloodAmounts[$bloodType->value]);
    }

    /**
     * Add plasma to the stock for a specific plasma type.
     */
    public function addToPlasmaStock(BloodTypeEnum $bloodType, float $amountToAdd): void
    {
        $this->plasmaAmounts[$bloodType->value] += $amountToAdd;

        // Update the database for plasma
        static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount, is_plasma) VALUES (?, ?, 1) 
             ON DUPLICATE KEY UPDATE amount = amount + ?",
            "sdd",
            $bloodType->value,
            $amountToAdd,
            $amountToAdd
        );

        $this->notifyBeneficiaries(DonationType::PLASMA, $bloodType, $this->plasmaAmounts[$bloodType->value]);
    }

    /**
     * Remove blood from the stock for a specific blood type.
     */
    public function removeFromBloodStock( BloodTypeEnum $bloodType, float $amountToRemove): ?bool
    {
        // Check if the requested amount is more than the available stock
        if ($this->bloodAmounts[$bloodType->value] < $amountToRemove) {
            // Return false to indicate insufficient stock
            return false;
        }

        // Proceed with removing the blood if sufficient stock exists
        $this->bloodAmounts[$bloodType->value] -= $amountToRemove;

        // Update the database with the new amount for blood
        static::executeUpdate(
            "UPDATE BloodStock SET amount = ? WHERE blood_type = ? AND is_plasma = 0",
            "ds",
            $this->bloodAmounts[$bloodType->value],
            $bloodType->value
        );

        $this->notifyBeneficiaries(DonationType::BLOOD, $bloodType, $this->bloodAmounts[$bloodType->value]);

        // Return true to indicate successful operation
        return true;
    }

    /**
     * Remove plasma from the stock for a specific plasma type.
     */
    public function removeFromPlasmaStock( BloodTypeEnum $bloodType, float $amountToRemove): ?bool
    {
        // Check if the requested amount is more than the available plasma stock
        if ($this->plasmaAmounts[$bloodType->value] < $amountToRemove) {
            // Return false to indicate insufficient stock
            return false;
        }

        // Proceed with removing plasma if sufficient stock exists
        $this->plasmaAmounts[$bloodType->value] -= $amountToRemove;

        // Update the database with the new amount for plasma
        static::executeUpdate(
            "UPDATE BloodStock SET amount = ? WHERE blood_type = ? AND is_plasma = 1",
            "ds",
            $this->plasmaAmounts[$bloodType->value],
            $bloodType->value
        );

        $this->notifyBeneficiaries(DonationType::PLASMA, $bloodType, $this->plasmaAmounts[$bloodType->value]);

        // Return true to indicate successful operation
        return true;
    }

    /**
     * Get the current stock for a specific blood type.
     */
    public function getBloodStock(BloodTypeEnum $bloodType): float
    {
        return $this->bloodAmounts[$bloodType->value];
    }

    /**
     * Get the current plasma stock for a specific plasma type.
     */
    public function getPlasmaStock(BloodTypeEnum $bloodType): float
    {
        return $this->plasmaAmounts[$bloodType->value];
    }

    /**
     * Get all blood stocks as a map.
     */
    public function getAllBloodStocks(): array
    {
        return $this->bloodAmounts;
    }

    /**
     * Get all plasma stocks as a map.
     */
    public function getAllPlasmaStocks(): array
    {
        return $this->plasmaAmounts;
    }

    // Observer pattern methods
    public function addBeneficiary(IBeneficiary $beneficiary): void
    {
        $this->listOfBeneficiaries[] = $beneficiary;
    }

    public function removeBeneficiary(IBeneficiary $beneficiary): void
    {
        $this->listOfBeneficiaries = array_filter(
            $this->listOfBeneficiaries,
            fn($b) => $b !== $beneficiary
        );
    }

    public function notifyBeneficiaries(DonationType $bloodDonationType, BloodTypeEnum $bloodType, float $amount): void
    {
        foreach ($this->listOfBeneficiaries as $beneficiary) {
            $beneficiary->update($bloodDonationType, $bloodType, $amount);
        }
    }
}

?>