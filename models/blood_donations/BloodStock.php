<?php

// BloodStock, Singleton
class BloodStock extends Model implements IBloodStock {
    private static ?BloodStock $instance = null;
    private int $id;
    private string $blood_type;
    private float $amount;
    private array $listOfBeneficiaries = [];

    private function __construct(int $id) {
        $row = $this->fetchSingle("SELECT * FROM BloodStock WHERE id = ?", "i", $id);
        if ($row) {
            $this->id = (int)$row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount = (float)$row['amount'];
        } else {
            throw new Exception("BloodStock with ID $id not found.");
        }
    }

    public static function getInstance(int $id = 1): BloodStock {
        if (self::$instance === null) {
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    public static function create(string $blood_type, float $amount): BloodStock {
        $id = static::executeUpdate(
            "INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)",
            "sd",
            $blood_type,
            $amount
        );
        return new BloodStock($id);
    }

    public function update(float $new_amount): void {
        static::executeUpdate(
            "UPDATE BloodStock SET amount = ? WHERE id = ?",
            "di",
            $new_amount,
            $this->id
        );
        $this->amount = $new_amount;
    }

    public function delete(): void {
        static::executeUpdate(
            "DELETE FROM BloodStock WHERE id = ?",
            "i",
            $this->id
        );
        self::$instance = null;
    }

    public function addBeneficiary(IBeneficiaries $beneficiary): void {
        $this->listOfBeneficiaries[] = $beneficiary;
    }

    public function removeBeneficiary(IBeneficiaries $beneficiary): void {
        $this->listOfBeneficiaries = array_filter(
            $this->listOfBeneficiaries,
            fn($b) => $b !== $beneficiary
        );
    }

    public function updateBloodStock(): void {
        foreach ($this->listOfBeneficiaries as $beneficiary) {
            $beneficiary->update();
        }
    }
}

?>