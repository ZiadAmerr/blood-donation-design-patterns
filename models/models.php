<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

// Address (extends Model for DB operations)
class Address extends Model
{
    public int $id;
    public string $name;
    public ?int $parent_id;

    // Constructor
    public function __construct(int $id) {
        $data = self::fetchSingle("SELECT * FROM Address WHERE id = ?", "i", $id);

        if (!$data) {
            throw new Exception("Address with ID $id not found.");
        }

    
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->parent_id = $data['parent_id'] !== null ? (int) $data['parent_id'] : null;
    }

    // Static method to create a new Address
    public static function create(string $name, ?int $parent_id = null): Address
    {
        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        $id = self::executeUpdate(
            "INSERT INTO Address (name, parent_id) VALUES (?, ?)",
            "si",
            $name,
            $parent_id
        );

        return new Address($id);
    }

    // Method to update an existing Address
    public function update(string $name, ?int $parent_id = null): void
    {
        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        self::executeUpdate(
            "UPDATE Address SET name = ?, parent_id = ? WHERE id = ?",
            "sii",
            $name,
            $parent_id,
            $this->id
        );

        $this->name = $name;
        $this->parent_id = $parent_id;
    }

    // Method to delete an Address
    public function delete(): void
    {
        self::executeUpdate(
            "DELETE FROM Address WHERE id = ?",
            "i",
            $this->id
        );
    }

    // Static method to validate if a parent_id exists in the database
    private static function validateParentId(int $parent_id): void
    {
        $exists = self::fetchSingle(
            "SELECT id FROM Address WHERE id = ?",
            "i",
            $parent_id
        );

        if (!$exists) {
            throw new Exception("Parent Address with ID $parent_id does not exist.");
        }
    }

    // Helper to fetch the parent Address as an object (if applicable)
    public function getParent(): ?Address
    {
        return $this->parent_id !== null ? new Address($this->parent_id) : null;
    }
}

// Abstract Person (extends Model for DB operations)
abstract class Person extends Model {
    protected $id;
    protected $name;
    protected $date_of_birth;
    protected $national_id;
    protected $address;

    public function __construct($id) {
        $row = $this->fetchSingle("SELECT * FROM Person WHERE id = ?", "i", $id);
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->date_of_birth = $row['date_of_birth'];
            $this->national_id = $row['national_id'];
            $this->address = new Address($row['address_id']);
        }
    }

    public static function create($name, $date_of_birth, $national_id, $address_id) {
        $id = static::executeUpdate(
            "INSERT INTO Person (name, date_of_birth, national_id, address_id) VALUES (?, ?, ?, ?)",
            "sssi",
            $name, $date_of_birth, $national_id, $address_id
        );
        return new static($id);
    }

    public function update($name, $date_of_birth, $national_id, $address_id) {
        static::executeUpdate(
            "UPDATE Person SET name = ?, date_of_birth = ?, national_id = ?, address_id = ? WHERE id = ?",
            "sssii",
            $name, $date_of_birth, $national_id, $address_id, $this->id
        );
    }

    public function delete() {
        static::executeUpdate("DELETE FROM Person WHERE id = ?", "i", $this->id);
    }
}


// Donor Model (extends Person)
class Donor extends Person {
    public int $person_id;

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->person_id = $person_id;
    }

    public static function create($name, $date_of_birth, $national_id, $address_id): Donor {
        $person = parent::create($name, $date_of_birth, $national_id, $address_id);

        static::executeUpdate(
            "INSERT INTO Donor (person_id) VALUES (?)",
            "i",
            $person->id
        );

        return new Donor($person->id);
    }

    public function delete(): void {
        static::executeUpdate(
            "DELETE FROM Donor WHERE person_id = ?",
            "i",
            $this->person_id
        );

        parent::delete();
    }
}

// Donation (extends Model for DB operations)
class Donation extends Model {
    public int $id;
    public Donor $donor;
    public string $type;

    public function __construct(int $id) {
        $row = $this->fetchSingle("SELECT * FROM Donation WHERE id = ?", "i", $id);
        if ($row) {
            $this->id = (int)$row['id'];
            $this->type = $row['type'];
            $this->donor = new Donor((int)$row['donor_id']);
        } else {
            throw new Exception("Donation with ID $id not found.");
        }
    }

    public static function create(int $donor_id, string $type): Donation {
        $id = static::executeUpdate(
            "INSERT INTO Donation (donor_id, type) VALUES (?, ?)",
            "is",
            $donor_id,
            $type
        );
        return new Donation($id);
    }

    public function delete(): void {
        static::executeUpdate(
            "DELETE FROM Donation WHERE id = ?",
            "i",
            $this->id
        );
    }
}

// BloodStock, Singleton
class BloodStock extends Model {
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

    public function addBeneficiary(Beneficiaries $beneficiary): void {
        $this->listOfBeneficiaries[] = $beneficiary;
    }

    public function removeBeneficiary(Beneficiaries $beneficiary): void {
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

