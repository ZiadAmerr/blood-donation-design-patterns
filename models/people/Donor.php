<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodTypeEnum.php';
require_once 'Person.php';

// Donor Model (extends Person)
class Donor extends Person {
    public int $person_id;
    public BloodTypeEnum $blood_type;
    public const table_name = "donors";

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

        // Fetch donor's blood type
        $this->blood_type = $this->fetchBloodTypeFromDB();

        // Fetch diseases if stored in DB
        $this->diseases = $this->fetchDiseasesFromDB();

        // If no diseases stored, randomly assign diseases (10% chance)
        if (empty($this->diseases) && rand(0, 9) === 0) {
            $this->diseases = (array) array_intersect_key(
                static::$permanently_ineligible_diseases,
                array_flip(array_rand(static::$permanently_ineligible_diseases, rand(1, count(static::$permanently_ineligible_diseases))))
            );
        }

        // Fetch all donations associated with this donor
        $this->loadDonations();
    }

    /**
     * Create a new donor if not exists, otherwise return existing one
     */
    public static function create(
        string $name, 
        string $date_of_birth, 
        string $phone_number, 
        string $national_id, 
        string $username, 
        string $password, 
        int $address_id, 
        string $blood_type = null,
    ): int {
        // Ensure person exists
        $person_id = Person::create($name, $date_of_birth, $phone_number, $national_id, $username, $password, $address_id);

        // Check if donor already exists
        $existing = static::fetchSingle("SELECT person_id FROM donors WHERE person_id = ?", "i", $person_id);

        if ($existing) {
            return $existing['person_id']; // Return existing donor's ID
        }

        // Insert new donor
        static::executeUpdate(
            "INSERT INTO " . self::table_name . " (person_id, blood_type) VALUES (?, ?)",
            "is",
            $person_id, 
            $blood_type
        );

        return $person_id; // Return new donor's ID
    }


    /**
     * Fetch donor blood type from the database
     */
    private function fetchBloodTypeFromDB(): BloodTypeEnum {
        $row = $this->fetchSingle("SELECT blood_type FROM donors WHERE person_id = ?", "i", $this->person_id);
        if ($row) {
            return BloodTypeEnum::from($row['blood_type']);
        }
        throw new Exception("Blood type not found for donor ID: {$this->person_id}");
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
    public function delete(): void {
        static::executeUpdate(
            "DELETE FROM donors WHERE person_id = ?",
            "i",
            $this->person_id
        );

        parent::delete();
    }
}
?>
