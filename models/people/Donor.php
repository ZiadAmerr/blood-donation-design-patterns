<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Person.php';

// Donor Model (extends Person)
class Donor extends Person {
    public int $person_id;

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->person_id = $person_id;
    }

    public static function create(string $name, string $dob, string $nationalId, string $address, string $phone): int
    {
        $sql = "INSERT INTO Donor (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)";
        return self::executeUpdate($sql, 'sssss', $name, $dob, $nationalId, $address, $phone);
    }

    // public function delete(): void {
    //     static::executeUpdate(
    //         "DELETE FROM Donor WHERE person_id = ?",
    //         "i",
    //         $this->person_id
    //     );

    //     parent::delete();
    // }
}

?>