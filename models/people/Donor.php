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

    public static function findByNationalId(string $nationalId): ?Donor {
        $row = self::fetchSingle("SELECT * FROM Donor WHERE national_id = ?", "s", $nationalId);

        if ($row) {
            $donor = new Donor($row['national_id']);
            return $donor;
        }
        return null;
    }
}

?>