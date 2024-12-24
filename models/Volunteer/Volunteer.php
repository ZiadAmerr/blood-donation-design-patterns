<?php


require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once 'Donor.php';

class Volunteer extends Donor implements IVolunteer {
    public array $skills = [];

    public function __construct(int $person_id) {
        parent::__construct($person_id);
        $this->loadSkills();
    }

    // Add a skill and save it to the database
    public function addSkill(string $skill): void {
        $this->skills[] = $skill;

        // Save skill to the database
        static::executeUpdate(
            "INSERT INTO VolunteerSkills (person_id, skill) VALUES (?, ?)",
            "is",
            $this->person_id,
            $skill
        );
    }

    // Load skills from the database
    private function loadSkills(): void {
        $rows = $this->fetchAll(
            "SELECT skill FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );

        foreach ($rows as $row) {
            $this->skills[] = $row['skill'];
        }
    }

    // Remove a skill from the database
    public function removeSkill(string $skill): void {
        $this->skills = array_filter($this->skills, fn($s) => $s !== $skill);

        // Remove skill from the database
        static::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ? AND skill = ?",
            "is",
            $this->person_id,
            $skill
        );
    }

    // Delete volunteer (skills and donor record)
    public function delete(): void {
        // Delete all skills from the database
        static::executeUpdate(
            "DELETE FROM VolunteerSkills WHERE person_id = ?",
            "i",
            $this->person_id
        );

        // Call parent delete to remove the Donor and Person records
        parent::delete();
    }
}


?>