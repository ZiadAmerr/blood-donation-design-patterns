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

?>