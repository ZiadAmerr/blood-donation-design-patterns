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
    public $id;
    public $name;
    public $parent_id;

    public function __construct($id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM Address WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->parent_id = $row['parent_id'];
        }
    }

    public static function create($name, $parent_id = null) {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Address (name, parent_id) VALUES (?, ?)");
        $query->bind_param("si", $name, $parent_id);
        $query->execute();
        return new Address($db->insert_id);
    }

    public function update($name, $parent_id = null) {
        $db = Database::getInstance();
        $query = $db->prepare("UPDATE Address SET name = ?, parent_id = ? WHERE id = ?");
        $query->bind_param("sii", $name, $parent_id, $this->id);
        $query->execute();
    }

    public function delete() {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Address WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();
    }
}

// Donor Model
class Donor extends Person {
    public static function create($person_id) {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Donor (person_id) VALUES (?)");
        $query->bind_param("i", $person_id);
        $query->execute();
        return new Donor($person_id);
    }

    public function delete() {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Donor WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();
        parent::delete();
    }
}

// Donation Model
class Donation {
    public $id;
    public $donor;
    public $type;

    public function __construct($id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM Donation WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->type = $row['type'];
            $this->donor = new Donor($row['donor_id']);
        }
    }

    public static function create($donor_id, $type) {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO Donation (donor_id, type) VALUES (?, ?)");
        $query->bind_param("is", $donor_id, $type);
        $query->execute();
        return new Donation($db->insert_id);
    }

    public function delete() {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM Donation WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();
    }

    // BloodStock Singleton Model
class BloodStock {
    private static $instance = null;
    public $id;
    public $blood_type;
    public $amount;

    private function __construct($id) {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT * FROM BloodStock WHERE id = ?");
        $query->bind_param("i", $id);
        $query->execute();
        $result = $query->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['id'];
            $this->blood_type = $row['blood_type'];
            $this->amount = $row['amount'];
        }
    }

    // Singleton instance
    public static function getInstance($id = 1) {
        if (self::$instance === null) {
            self::$instance = new BloodStock($id);
        }
        return self::$instance;
    }

    // Create a new blood stock record
    public static function create($blood_type, $amount) {
        $db = Database::getInstance();
        $query = $db->prepare("INSERT INTO BloodStock (blood_type, amount) VALUES (?, ?)");
        $query->bind_param("sd", $blood_type, $amount);
        $query->execute();
        return new BloodStock($db->insert_id);
    }

    // Update the blood stock amount
    public function update($new_amount) {
        $db = Database::getInstance();
        $query = $db->prepare("UPDATE BloodStock SET amount = ? WHERE id = ?");
        $query->bind_param("di", $new_amount, $this->id);
        $query->execute();
        $this->amount = $new_amount; // Update the current instance's value
    }

    // Delete the blood stock record
    public function delete() {
        $db = Database::getInstance();
        $query = $db->prepare("DELETE FROM BloodStock WHERE id = ?");
        $query->bind_param("i", $this->id);
        $query->execute();
        self::$instance = null; // Reset the Singleton instance
    }
}

}
?>

