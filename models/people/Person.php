<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

// Abstract Person (extends Model for DB operations)
abstract class Person extends Model {
    protected $id;
    protected $name;
    protected $date_of_birth;
    protected $national_id;
    protected $address;
    protected $phone_number;

    public function __construct($id) {
        $row = $this->fetchSingle("SELECT * FROM Person WHERE id = ?", "i", $id);
        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->date_of_birth = $row['date_of_birth'];
            $this->national_id = $row['national_id'];
            $this->phone_number = $row['phone_number'];
            $this->address = new Address($row['address_id']);
        }
    }

    // public static function create($name, $date_of_birth, $national_id, $address_id, $phone_number) {
    //     $id = static::executeUpdate(
    //         "INSERT INTO Person (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)",
    //         "sssis",
    //         $name, $date_of_birth, $national_id, $address_id, $phone_number
    //     );
    //     return new static($id);
    // }

    // public function update($name, $date_of_birth, $national_id, $address_id, $phone_number) {
    //     static::executeUpdate(
    //         "UPDATE Person SET name = ?, date_of_birth = ?, national_id = ?, address_id = ?, phone_number = ? WHERE id = ?",
    //         "sssisi",
    //         $name, $date_of_birth, $national_id, $address_id, $this->id, $phone_number
    //     );
    // }

    // public function delete() {
    //     static::executeUpdate("DELETE FROM Person WHERE id = ?", "i", $this->id);
    // }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDateOfBirth() {
        return $this->date_of_birth;
    }

    public function getNationalId() {
        return $this->national_id;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getAddress() {
        return $this->address;
    }
}

?>