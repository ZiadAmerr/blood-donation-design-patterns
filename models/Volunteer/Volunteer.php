<?php
// File: /models/people/Volunteer.php

require_once __DIR__ . '/Donor.php';
require_once __DIR__ . '/../../services/database_service.php';  // Adjust path

class Volunteer extends Donor
{
    public bool $isAvailable = true;
    public array $skills = [];
    public int $totalHours = 0;

    public function __construct(int $person_id)
    {
        parent::__construct($person_id);
        // Load extra volunteer data from DB if needed
        // For example, isAvailable from a volunteer table, or store in Donor table as well
        $this->loadSkills();
    }

    // 1) CREATE a new volunteer
    public static function createVolunteer(
        string $name,
        string $dob,
        string $nationalId,
        int    $addressId,
        string $phone
    ): ?Volunteer {
        try {
            // Insert into Donor (which also usually inserts Person).
            // If you already have Donor::create(...) that returns the new donor's ID:
            $donorId = Donor::create($name, $dob, $nationalId, (string) $addressId, $phone);
            if (!$donorId) {
                return null;
            }
            // Then instantiate as a Volunteer
            return new Volunteer($donorId);
        } catch (Exception $e) {
            error_log("Volunteer creation error: " . $e->getMessage());
            return null;
        }
    }

    // 2) UPDATE volunteer data
    public function updateVolunteer(string $name, string $phone, bool $isAvailable): void
    {
        // For example, update Donor table:
        self::executeUpdate(
            "UPDATE Donor SET name = ?, phone_number = ? WHERE person_id = ?",
            "ssi",
            $name,
            $phone,
            $this->person_id
        );

        // Possibly store isAvailable in a separate volunteer table:
        // self::executeUpdate("UPDATE VolunteerTable SET is_available = ? WHERE person_id = ?", "ii", $isAvailable?1:0, $this->person_id);

        // Update in-memory
        $this->name = $name;
        $this->phone_number = $phone;
        $this->isAvailable = $isAvailable;
    }

    // 3) DELETE volunteer
    public function deleteVolunteer(): void
    {
        // If you keep volunteer fields in a separate table:
        // self::executeUpdate("DELETE FROM VolunteerTable WHERE person_id = ?", "i", $this->person_id);

        // Then remove from Donor/Person
    }

    // 4) SKILLS
    private function loadSkills(): void
    {
        $rows = self::fetchAll(
            "SELECT skill FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );
        foreach ($rows as $row) {
            $this->skills[] = $row['skill'];
        }
    }

    public function addSkill(string $skill): void
    {
        // Insert row
        self::executeUpdate(
            "INSERT INTO VolunteerSkills (person_id, skill) VALUES (?, ?)",
            "is",
            $this->person_id,
            $skill
        );
        $this->skills[] = $skill;
    }

    public function removeSkill(string $skill): void
    {
        // Delete row
        self::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ? AND skill = ?",
            "is",
            $this->person_id,
            $skill
        );
        $this->skills = array_filter($this->skills, fn($s) => $s !== $skill);
    }

    // 5) LIST volunteers: Typically you’d do a method like “allVolunteers()”
    public static function allVolunteers(): array
    {
        $rows = self::fetchAll("SELECT person_id FROM Donor"); // or a custom query that only returns volunteers
        $volunteers = [];
        foreach ($rows as $row) {
            try {
                $volunteers[] = new Volunteer($row['person_id']);
            } catch (Exception $e) {
                error_log("Error loading volunteer id " . $row['person_id'] . ": " . $e->getMessage());
            }
        }
        return $volunteers;
    }
}
