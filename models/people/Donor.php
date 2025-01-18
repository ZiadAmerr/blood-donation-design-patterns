<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Donation.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/blood_donations/BloodTypeEnum.php';
require_once 'Person.php';

// Donor Model (extends Person)
class Donor extends Person {
    public int $person_id;
    public BloodTypeEnum $blood_type;
    public float $weight;
    public const table_name = "donors";
    public int $age;

    /** @var int[] List of disease IDs that the donor has */
    public array $diseases = [];

    /** @var Donation[] List of donations */
    public array $blooddonations = [];

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->person_id = $person_id;

        // Fetch donor's blood type & weight
        $this->fetchDonorInfoFromDB();

        // Fetch diseases if stored in DB
        $this->diseases = $this->fetchDiseasesFromDB();

        // Fetch all donations associated with this donor
        // $this->loadbloodDonations();
        $date_of_birth = new DateTime($this->date_of_birth);
        $current_date = new DateTime();
        $this->age = $current_date->diff($date_of_birth)->y;
    }
  
    /**
     * Load all donations associated with this donor
     */
    public function getDonorLastDonationInterval(): int {

        $rows = $this->fetchAll(
            "SELECT id FROM blooddonations WHERE donor_id = ?",
            "i",
            $this->person_id
        );
        $donations = BloodDonation::fetchAllBloodDonations("SELECT * FROM BloodDonation");
        $mostRecentDate = null;

        foreach ($donations as $donation) {
            $donationDate = new DateTime($donation['date']);
            
            if ($mostRecentDate === null || $donationDate > $mostRecentDate) {
                $mostRecentDate = $donationDate;
            }
        }
        
        if ($mostRecentDate !== null) {
            $currentDate = new DateTime();
            $interval = $currentDate->diff($mostRecentDate);
        return $interval->days;
        }else{
            return 0;
        }
    }
  
    /**
     * Create a new donor if not exists, otherwise return existing one.
     * Also assigns diseases to the donor.
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
        float $weight = null,
        array $disease_ids = null
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
            "INSERT INTO " . self::table_name . " (person_id, blood_type, weight) VALUES (?, ?, ?)",
            "isd",
            $person_id, 
            $blood_type,
            $weight
        );

        // Assign diseases to the donor
        if (!empty($disease_ids)) {
            static::assignDiseasesToDonor($person_id, $disease_ids);
        }

        return $person_id; // Return new donor's ID
    }

    /**
     * Assign diseases to a donor.
     */
    private static function assignDiseasesToDonor(int $donor_id, array $disease_ids): void {
        if (empty($disease_ids)) {
            return;
        }

        $values = [];
        $types = str_repeat("ii", count($disease_ids)); // "ii" for each (donor_id, disease_id) pair

        foreach ($disease_ids as $disease_id) {
            $values[] = $donor_id;
            $values[] = $disease_id;
        }

        $placeholders = implode(", ", array_fill(0, count($disease_ids), "(?, ?)"));
        $query = "INSERT IGNORE INTO donor_diseases (donor_id, disease_id) VALUES $placeholders";

        static::executeUpdate($query, $types, ...$values);
    }

    /**
     * Fetch donor blood type & weight from the database.
     */
    private function fetchDonorInfoFromDB(): void {
        $row = $this->fetchSingle("SELECT blood_type, weight FROM donors WHERE person_id = ?", "i", $this->person_id);
        if ($row) {
            $this->blood_type = BloodTypeEnum::fromString((string)$row['blood_type']);
            $this->weight = (float) $row['weight'];
        } else {
            throw new Exception("Blood type & weight not found for donor ID: {$this->person_id}");
        }
    }

    /**
     * Fetch donor diseases from the database
     * @return int[] List of disease IDs
     */
    private function fetchDiseasesFromDB(): array {
        $rows = $this->fetchAll("SELECT disease_id FROM donor_diseases WHERE donor_id = ?", "i", $this->person_id);
        return $rows ? array_column($rows, 'disease_id') : [];
    }

    /**
     * Get diseases of the donor
     * @return int[]
     */
    public function getDiseases(): array {
        return $this->diseases;
    }
    public static function getDonorNameById(int $id): ?string
    {
        // Query to fetch the donor name by ID
        $sql = "SELECT name FROM Donor WHERE id = ?";

        // Fetch the donor name
        $row = self::fetchSingle($sql, "i", $id);

        // If no donor is found, return null
        if (!$row) {
            return null;
        }

        // Return the name
        return $row['name'];
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

    /**
     * Get user by username
     */
    public static function findByUsername(string $username): int {
        return Person::findByUsername($username);
    }

    /**
     * Get as JSON, as all string or numeric values
     */
    public function getAsJson(): array {
        return [
            'person_id' => $this->person_id,
            'name' => $this->name,
            'date_of_birth' => $this->date_of_birth,
            'phone_number' => $this->phone_number,
            'national_id' => $this->national_id,
            'username' => $this->username,
            'blood_type' => $this->blood_type->getAsValue(),
            'weight' => $this->weight,
            'diseases' => $this->diseases,
            'donations' => array_map(fn($donation) => $donation->jsonSerialize(), $this->blooddonations)
        ];
    }

    /**
     * Get the blood type of the donor
     */
    public function getBloodType(): BloodTypeEnum {
        return $this->blood_type;
    }

    public function getBloodTypeString(): string {
        return $this->blood_type->getAsValue();
    }
}

?>