<?php
// File: BloodStock.php
require_once __DIR__ . '/IBloodStock.php';
require_once __DIR__ . '/Ibeneficiaries.php';
require_once __DIR__ . '/database_service.php';

class BloodStock extends Model implements IBloodStock
{
    private static ?BloodStock $instance = null;

    private BloodTypeEnum $blood_type;
    private float $amount;

    // Observers
    private array $listOfBeneficiaries = [];

    /**
     * Private constructor ensures only one instance can be created.
     */
    private function __construct(int $id)
    {
        $row = $this->fetchSingle("SELECT * FROM BloodStock WHERE id = ?", "i", $id);
        if ($row) {
            // Initialize properties with fetched values
            $this->id         = (int) $row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount     = (float) $row['amount'];
        } else {
            // Throw exception if no record is found for the given ID
            throw new Exception("BloodStock with ID $id not found.");
        }
    }

    /**
     * Singleton accessor
     */
    public static function getInstance(int $id = 1): BloodStock {
        if (self::$instance === null) {
            // Create a new instance if one does not already exist
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    /**
     * Create a new BloodStock record
     */
    public static function create(BloodTypeEnum $blood_type, float $amount): BloodStock
    {
        $id = static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)",
            "sd",
            $blood_type,
            $amount
        );
        // Return a new BloodStock instance with the generated ID
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

        // Update the local property
        $this->amount = $new_amount;
        $this->updateBloodStock(); // notify observers
    }

    /**
     * Delete record from DB, reset instance
     */
    public function delete(): void
    {
        static::executeUpdate(
            "DELETE FROM BloodStock WHERE id = ?",
            "i",
            $this->id
        );
        
        self::$instance = null;
    }

    // -- Observer pattern methods --
    public function addBeneficiary(IBeneficiaries $beneficiary): void {
        $this->listOfBeneficiaries[] = $beneficiary;
    }

    // Remove a beneficiary from the list of observers
    public function removeBeneficiary(IBeneficiaries $beneficiary): void {
        $this->listOfBeneficiaries = array_filter(
            $this->listOfBeneficiaries,
            fn($b) => $b !== $beneficiary
        );
    }

    // Notify all beneficiaries about changes to the BloodStock
    public function updateBloodStock(): void {
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
