<?php
// File: BloodStock.php
require_once __DIR__ . '/IBloodStock.php';
require_once __DIR__ . '/Ibeneficiaries.php';
require_once __DIR__ . '/database_service.php'; // for Model
// Adjust the path if needed

class BloodStock extends Model implements IBloodStock
{
    private static ?BloodStock $instance = null;

    private int $id;
    private string $blood_type;  // e.g. "O+", "A-", etc.
    private float $amount;       // amount in liters

    // Observers
    private array $listOfBeneficiaries = [];

    /**
     * Private constructor ensures only one instance can be created.
     */
    private function __construct(int $id)
    {
        $row = $this->fetchSingle("SELECT * FROM BloodStock WHERE id = ?", "i", $id);
        if ($row) {
            $this->id         = (int) $row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount     = (float) $row['amount'];
        } else {
            throw new \Exception("BloodStock with ID $id not found.");
        }
    }

    /**
     * Singleton accessor
     */
    public static function getInstance(int $id = 1): BloodStock
    {
        if (self::$instance === null) {
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    /**
     * Create a new BloodStock record
     */
    public static function create(string $blood_type, float $amount): BloodStock
    {
        $id = static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)",
            "sd",
            $blood_type,
            $amount
        );
        return new BloodStock($id);
    }

    /**
     * Update the amount in DB and in this instance
     */
    public function update(float $new_amount): void
    {
        static::executeUpdate(
            "UPDATE BloodStock SET amount = ? WHERE id = ?",
            "di",
            $new_amount,
            $this->id
        );

        $this->amount = $new_amount;
        $this->updateBloodStock(); // notify observers
    }

    /**
     * Delete record from DB, reset instance
     */
    public function delete(): void
    {
        static::executeUpdate("DELETE FROM BloodStock WHERE id = ?", "i", $this->id);
        self::$instance = null;
    }

    // -- Observer pattern methods --

    public function addBeneficiary(IBeneficiaries $beneficiary): void
    {
        $this->listOfBeneficiaries[] = $beneficiary;
    }

    public function removeBeneficiary(IBeneficiaries $beneficiary): void
    {
        $this->listOfBeneficiaries = array_filter(
            $this->listOfBeneficiaries,
            fn($b) => $b !== $beneficiary
        );
    }

    public function updateBloodStock(): void
    {
        foreach ($this->listOfBeneficiaries as $beneficiary) {
            $beneficiary->update();
        }
    }

    // -- Convenience methods --

    public function addToStock(float $amountToAdd): void
    {
        $this->update($this->amount + $amountToAdd);
    }

    public function removeFromStock(float $amountToRemove): bool
    {
        if ($this->amount >= $amountToRemove) {
            $this->update($this->amount - $amountToRemove);
            return true;
        }
        return false; // insufficient stock
    }

    // -- Getters --

    public function getId(): int
    {
        return $this->id;
    }

    public function getBloodType(): string
    {
        return $this->blood_type;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
