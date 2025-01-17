<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Person.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';

// Donor Model (extends Person)
class Donor extends Person {
    public int $person_id;

    /** @var string[] List of diseases that make a donor permanently ineligible */
    public static array $permanently_ineligible_diseases = [
        "HIV",
        "HBV",
        "HCV",
    ];

    /** @var string[] List of donor's diseases */
    public array $diseases = [];

    /** @var Donation[] List of donations */
    public array $donations = [];

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->person_id = $person_id;

        // Fetch diseases if stored in DB (Modify based on DB structure)
        $this->diseases = $this->fetchDiseasesFromDB();

        // If no diseases stored, randomly assign diseases (10% chance)
        if (empty($this->diseases) && rand(0, 9) === 0) {
            $this->diseases = array_rand(array_flip(static::$permanently_ineligible_diseases), rand(1, count(static::$permanently_ineligible_diseases)));
        }

        // Fetch all donations associated with this donor
        $this->loadDonations();
    }

    public static function create(string $name, string $dob, string $nationalId, string $address, string $phone): Donor
{
    // Ensure database connection is initialized
    self::$db = Database::getInstance();

    if (!isset(self::$db) || self::$db === null) {
        throw new Exception("Database connection is not initialized.");
    }

    $sql = "INSERT INTO Donor (name, date_of_birth, national_id, address_id, phone_number) VALUES (?, ?, ?, ?, ?)";

    // Execute the update
    self::executeUpdate($sql, 'sssss', $name, $dob, $nationalId, $address, $phone);

    // Retrieve the last inserted ID
    $donorId = self::$db->insert_id;

    // Ensure donorId is valid
    if ($donorId <= 0) {
        throw new Exception("Failed to insert donor: " . self::$db->error);
    }

    return new Donor($donorId);
}


  
    /**
     * Fetch donor diseases from the database
     * @return string[]
     */
    private function fetchDiseasesFromDB(): array {
        $rows = $this->fetchAll("SELECT disease FROM DonorDiseases WHERE person_id = ?", "i", $this->person_id);
        return $rows ? array_column($rows, 'disease') : [];
    }

    /**
     * Load all donations associated with this donor
     */
    private function loadDonations(): void {
        $rows = $this->fetchAll("SELECT id FROM Donation WHERE donor_id = ?", "i", $this->person_id);
        foreach ($rows as $row) {
            $this->donations[] = new Donation($row['id']);
        }
    }

    /**
     * Get diseases of the donor
     * @return string[]
     */
    public function getDiseases(): array {
        return $this->diseases;
    }

    /**
     * Delete the donor
     */
    // public function delete(): void {
    //     static::executeUpdate(
    //         "DELETE FROM Donor WHERE person_id = ?",
    //         "i",
    //         $this->person_id
    //     );
    // 
    //     parent::delete();
    // }
}
