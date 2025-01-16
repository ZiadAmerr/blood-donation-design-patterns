<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/IBloodStock.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/BloodTypeEnum.php";

// BloodStock, Singleton
class BloodStock extends Model implements IBloodStock {
    // Static instance to hold the singleton object
    private static ?BloodStock $instance = null;

    // Attributes representing BloodStock details
    private int $id;
    private BloodTypeEnum $blood_type;
    private float $amount;

    // List of beneficiaries observing changes in BloodStock
    private array $listOfBeneficiaries = [];

    // Private constructor to enforce singleton pattern
    private function __construct(int $id) {
        // Fetch BloodStock details from the database using the provided ID
        $row = $this->fetchSingle("SELECT * FROM BloodStock WHERE id = ?", "i", $id);
        if ($row) {
            // Initialize properties with fetched values
            $this->id = (int)$row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount = (float)$row['amount'];
        } else {
            // Throw exception if no record is found for the given ID
            throw new Exception("BloodStock with ID $id not found.");
        }
    }

    // Static method to get the singleton instance, optionally initializing it with an ID
    public static function getInstance(int $id = 1): BloodStock {
        if (self::$instance === null) {
            // Create a new instance if one does not already exist
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    // Static method to create a new BloodStock entry in the database
    public static function create(string $blood_type, float $amount): BloodStock {
        // Insert a new record and get its ID
        $id = static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)",
            "sd",
            $blood_type,
            $amount
        );
        // Return a new BloodStock instance with the generated ID
        return new BloodStock($id);
    }

    // Update the blood stock amount in the database and locally
    public function update(float $new_amount): void {
        // Update the database with the new blood amount
        static::executeUpdate(
            "UPDATE BloodStock SET amount = ? WHERE id = ?",
            "di",
            $new_amount,
            $this->id
        );
        // Update the local property
        $this->amount = $new_amount;
    }

    // Delete the BloodStock entry from the database and reset the singleton instance
    public function delete(): void {
        // Remove the record from the database
        static::executeUpdate(
            "DELETE FROM BloodStock WHERE id = ?",
            "i",
            $this->id
        );
        // Reset the singleton instance to null
        self::$instance = null;
    }

    // Add a beneficiary to the list of observers
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
}

?>