<?php

// Donation (extends Model for DB operations)
class Donation extends Model {
    public int $id;
    public Donor $donor;
    public string $type;

    public function __construct(int $id) {
        $row = $this->fetchSingle("SELECT * FROM Donation WHERE id = ?", "i", $id);
        if ($row) {
            $this->id = (int)$row['id'];
            $this->type = $row['type'];
            $this->donor = new Donor((int)$row['donor_id']);
        } else {
            throw new Exception("Donation with ID $id not found.");
        }
    }

    public static function create(int $donor_id, string $type): Donation {
        $id = static::executeUpdate(
            "INSERT INTO Donation (donor_id, type) VALUES (?, ?)",
            "is",
            $donor_id,
            $type
        );
        return new Donation($id);
    }

    public function delete(): void {
        static::executeUpdate(
            "DELETE FROM Donation WHERE id = ?",
            "i",
            $this->id
        );
    }
}

?>