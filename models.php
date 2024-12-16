<?php
// Database Singleton
class Database {
    private static $instance = null;
    private $conn;

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "sdp";

    private function __construct() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);
        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
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

// Address Model
class Address {
    public int $id;
    public string $name;
    public ?int $parent_id; // Nullable parent address ID

    // Constructor
    public function __construct(int $id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM Address WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();

        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->parent_id = $row['parent_id'] !== null ? (int)$row['parent_id'] : null;
        } else {
            throw new Exception("Address with ID $id not found.");
        }
    }

    // Static method to create a new Address
    public static function create(string $name, ?int $parent_id = null): Address {
        // Validate parent_id if not null
        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Address (name, parent_id) VALUES (?, ?)");
        $query->bind_param("si", $name, $parent_id);
        if (!$query->execute()) {
            throw new Exception("Failed to create Address: " . $query->error);
        }
        return new Address($db->insert_id);
    }

    // Method to update an existing Address
    public function update(string $name, ?int $parent_id = null): void {
        // Validate parent_id if not null
        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        $db = Database::getInstance();
        $query = $db->prepare("UPDATE Address SET name = ?, parent_id = ? WHERE id = ?");
        $query->bind_param("sii", $name, $parent_id, $this->id);
        if (!$query->execute()) {
            throw new Exception("Failed to update Address: " . $query->error);
        }
        $this->name = $name;
        $this->parent_id = $parent_id;
    }

    // Method to delete an Address
    public function delete(): void {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Address WHERE id = ?");
        $query->bind_param("i", $this->id);
        if (!$query->execute()) {
            throw new Exception("Failed to delete Address: " . $query->error);
        }
    }

    // Static method to validate if a parent_id exists in the database
    private static function validateParentId(int $parent_id): void {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT id FROM Address WHERE id = ?");
        $query->bind_param("i", $parent_id);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows === 0) {
            throw new Exception("Parent Address with ID $parent_id does not exist.");
        }
    }

    // Helper to fetch the parent Address as an object (if applicable)
    public function getParent(): ?Address {
        return $this->parent_id !== null ? new Address($this->parent_id) : null;
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

    // Create a new Donor record
    public static function create(int $person_id): Donor {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Donor (person_id) VALUES (?)");
        $query->bind_param("i", $person_id);

        if (!$query->execute()) {
            throw new Exception("Failed to create Donor: " . $query->error);
        }
        return new Donor($person_id);
    }

    // Delete the Donor record
    public function delete(): void {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Donor WHERE person_id = ?");
        $query->bind_param("i", $this->person_id);

        if (!$query->execute()) {
            throw new Exception("Failed to delete Donor: " . $query->error);
        }

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
class BloodStock {
    private static ?BloodStock $instance = null;
    public int $id;
    public string $blood_type;
    public float $amount;

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
}

?>

