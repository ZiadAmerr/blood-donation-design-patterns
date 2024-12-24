<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

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

?>