<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";

// Abstract Person (extends Model for DB operations)
abstract class Person extends Model {
    protected int $id;
    protected string $name;
    protected string $date_of_birth;
    protected string $phone_number;
    protected int $address_id;
    protected string $national_id;
    protected string $username;
    protected string $hashed_password;
    protected Address $address;

    public const table_name = "persons";

    public function __construct(int $id) {
        $row = $this->fetchSingle(
            "SELECT * FROM " . self::table_name . " WHERE id = ?",
            "i",
            $id
        );
        
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->date_of_birth = $row['date_of_birth'];
            $this->phone_number = $row['phone_number'];
            $this->national_id = $row['national_id'];
            $this->username = $row['username'];
            $this->hashed_password = $row['hashed_password'];
            $this->address_id = $row['address_id'];
            $this->address = new Address($this->address_id);
        } else {
            throw new Exception("Person with ID $id not found.");
        }
    }

    public static function create(
        string $name, 
        string $date_of_birth, 
        string $phone_number, 
        string $national_id, 
        string $username, 
        string $password, 
        int $address_id
    ): int {
        // Check if the person already exists
        $existing = static::fetchSingle("SELECT id FROM " . self::table_name . " WHERE national_id = ?", "s", $national_id);

        if ($existing) {
            return $existing['id']; // Return existing person's ID
        }

        // Insert new person
        static::executeUpdate(
            "INSERT INTO " . self::table_name . " (name, date_of_birth, phone_number, national_id, username, hashed_password, address_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)",
            "ssssssi",
            $name, $date_of_birth, $phone_number, $national_id, $username, md5($password), $address_id
        );

        // Retrieve newly created person ID
        $new_id = static::getLastInsertId();
        return $new_id; // Return new person's ID
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDateOfBirth(): string {
        return $this->date_of_birth;
    }

    public function getPhoneNumber(): string {
        return $this->phone_number;
    }

    public function getNationalId(): string {
        return $this->national_id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getAddress(): Address {
        return $this->address;
    }

    public function getPassword(): string {
        return $this->hashed_password;
    }

    public static function findByUsername(string $username): int {
        $row = static::fetchSingle(
            "SELECT id FROM " . self::table_name . " WHERE username = ?",
            "s",
            $username
        );
        
        if ($row) {
            return $row['id'];
        } else {
            return -1;
        }
    }
}

?>
