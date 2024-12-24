<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

// Address Model
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

// Abstract Person Model
abstract class Person {
    protected $id;
    protected $name;
    protected $date_of_birth;
    protected $national_id;
    protected $address;

    public function __construct($id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM Person WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->date_of_birth = $row['date_of_birth'];
            $this->national_id = $row['national_id'];
            $this->address = new Address($row['address_id']);
        }
    }

    public static function create($name, $date_of_birth, $national_id, $address_id) {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Person (name, date_of_birth, national_id, address_id) VALUES (?, ?, ?, ?)");
        $query->bind_param("sssi", $name, $date_of_birth, $national_id, $address_id);
        $query->execute();
        return new static($db->insert_id); // Return new object
    }

    public function update($name, $date_of_birth, $national_id, $address_id) {
        $db = Database::getInstance();
        $query = $db->prepare("UPDATE Person SET name = ?, date_of_birth = ?, national_id = ?, address_id = ? WHERE id = ?");
        $query->bind_param("sssii", $name, $date_of_birth, $national_id, $address_id, $this->id);
        $query->execute();
    }

    public function delete() {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Person WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();
    }
}


// Donor Model
class Donor extends Person {
    public int $person_id;

    // Constructor to initialize Donor
    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->person_id = $person_id;
    }

    // Override create method to match the parent signature
    public static function create($name, $date_of_birth, $national_id, $address_id): Donor {
        // Create a Person first
        $person = parent::create($name, $date_of_birth, $national_id, $address_id);

        // Insert into Donor table using the new Person ID
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Donor (person_id) VALUES (?)");
        $query->bind_param("i", $person->id);

        if (!$query->execute()) {
            throw new Exception("Failed to create Donor: " . $query->error);
        }

        // Return the Donor object
        return new Donor($person->id);
    }

    // Delete the Donor record
    public function delete(): void {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Donor WHERE person_id = ?");
        $query->bind_param("i", $this->person_id);

        if (!$query->execute()) {
            throw new Exception("Failed to delete Donor: " . $query->error);
        }

        // Delete the Person record as well
        parent::delete();
    }
}



// Donation Model
class Donation {
    public int $id;
    public Donor $donor;
    public string $type;

    // Constructor to initialize Donation
    public function __construct(int $id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM Donation WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->id = (int)$row['id'];
            $this->type = $row['type'];
            $this->donor = new Donor((int)$row['donor_id']);
        } else {
            throw new Exception("Donation with ID $id not found.");
        }
    }

    // Create a new Donation
    public static function create(int $donor_id, string $type): Donation {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Donation (donor_id, type) VALUES (?, ?)");
        $query->bind_param("is", $donor_id, $type);

        if (!$query->execute()) {
            throw new Exception("Failed to create Donation: " . $query->error);
        }

        return new Donation($db->insert_id);
    }

    // Delete the Donation record
    public function delete(): void {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Donation WHERE id = ?");
        if (!$query) {
            throw new Exception("Failed to prepare query: " . $db->error);
        }

        $query->bind_param("i", $this->id);

        if (!$query->execute()) {
            throw new Exception("Failed to delete Donation: " . $query->error);
        }
    }
}


// BloodStock Singleton Model
class BloodStock implements IBloodStock{
    private static ?BloodStock $instance = null;
    private int $id;
    private string $blood_type;
    private float $amount;
    private array $listOfBeneficiaries = []; // array of Beneficiary objects
    

    // Private constructor to enforce Singleton
    private function __construct(int $id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM BloodStock WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->id = (int)$row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount = (float)$row['amount'];
        } else {
            throw new Exception("BloodStock with ID $id not found.");
        }
    }

    // Singleton instance getter
    public static function getInstance(int $id = 1): BloodStock {
        if (self::$instance === null) {
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    // Create a new BloodStock record
    public static function create(string $blood_type, float $amount): BloodStock {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)");
        $query->bind_param("sd", $blood_type, $amount);

        if (!$query->execute()) {
            throw new Exception("Failed to create BloodStock: " . $query->error);
        }

        return new BloodStock($db->insert_id);
    }

    // Update the blood stock amount
    public function update(float $new_amount): void {
        $db = Database::getInstance();
        $query = $db->prepare("UPDATE BloodStock SET amount = ? WHERE id = ?");
        $query->bind_param("di", $new_amount, $this->id);

        if (!$query->execute()) {
            throw new Exception("Failed to update BloodStock: " . $query->error);
        }

        $this->amount = $new_amount; // Update instance property
    }

    // Delete the blood stock record and reset the Singleton instance
    public function delete(): void {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM BloodStock WHERE id = ?");
        $query->bind_param("i", $this->id);

        if (!$query->execute()) {
            throw new Exception("Failed to delete BloodStock: " . $query->error);
        }

        self::$instance = null; // Reset Singleton instance
        
    }
    public function addBeneficiary(Beneficiaries $beneficiary): void
    {
        // add a beneficiary to the list
        array_push($this->listOfBeneficiaries, $beneficiary);
    }
    public function removeBeneficiary(Beneficiaries $beneficiary): void
    {
        // remove a beneficiary from the list
        unset($this->listOfBeneficiaries[$beneficiary]);
        
    }
    public function updateBloodStock(): void
    {
        // this function is called as soon as any change in the blood stock occur to notify other beneficiaries of this change
        foreach ($this->listOfBeneficiaries as $beneficiary) 
        {
            $beneficiary->update();
        }
    }
}

?>

