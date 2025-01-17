<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/IBloodStock.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/IBeneficiary.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class BloodStock extends Model implements IBloodStock
{
    private static ?BloodStock $instance = null;
    private array $listOfBeneficiaries = [];
    private array $bloodAmounts;

    /**
     * Private constructor ensures only one instance can be created.
     */
    private function __construct()
    {
        // Initialize the blood amounts map with 0 liters for each blood type
        $this->bloodAmounts = array_fill_keys(BloodTypeEnum::getAllValues(), 0.0);

        // Optionally fetch data from the database to populate initial stock
        $rows = $this->fetchAll("SELECT blood_type, amount FROM BloodStock");
        foreach ($rows as $row) {
            $this->bloodAmounts[$row['blood_type']] = (float) $row['amount'];
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
    public function addToStock(BloodTypeEnum $bloodType, float $amountToAdd): void
    {
        $this->bloodAmounts[$bloodType->value] += $amountToAdd;

        // Update the database
        static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?) 
             ON DUPLICATE KEY UPDATE amount = amount + ?",
            "sdd",
            $bloodType->value,
            $amountToAdd,
            $amountToAdd
        );

        $this->notifyBeneficiaries($bloodType, $this->bloodAmounts[$bloodType->value]);
    }

    /**
     * Remove blood from the stock for a specific blood type.
     */
    public function removeFromStock(BloodTypeEnum $bloodType, float $amountToRemove): ?bool
{
    // Check if the requested amount is more than the available stock
    if ($this->bloodAmounts[$bloodType->value] < $amountToRemove) {
        // Return null to indicate insufficient stock
        return false;
    }

    // Proceed with removing the blood if sufficient stock exists
    $this->bloodAmounts[$bloodType->value] -= $amountToRemove;

    // Update the database with the new amount
    static::executeUpdate(
        "UPDATE BloodStock SET amount = ? WHERE blood_type = ?",
        "ds",
        $this->bloodAmounts[$bloodType->value],
        $bloodType->value
    );

    // Notify beneficiaries about the update
    $this->notifyBeneficiaries($bloodType, $this->bloodAmounts[$bloodType->value]);

    // Return true to indicate successful operation
    return true;
}


    /**
     * Get the current stock for a specific blood type.
     */
    public function getStock(BloodTypeEnum $bloodType): float
    {
        return $this->bloodAmounts[$bloodType->value];
    }

    /**
     * Get all blood stocks as a map.
     */
    public function getAllStocks(): array
    {
        return $this->bloodAmounts;
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

    public function notifyBeneficiaries(BloodTypeEnum $bloodType, float $amount): void
    {
        foreach ($this->listOfBeneficiaries as $beneficiary) {
            $beneficiary->update($bloodType, $amount);
        }
    }
}
?>