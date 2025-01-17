<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Donor.php";

class DonorService {
    public static function registerDonor($name, $date_of_birth, $national_id, $password, $address_id) {

        // Create a new Donor in the database
        return Donor::create($name, $date_of_birth, $national_id, $hashed_password, $address_id);
    }
}

?>