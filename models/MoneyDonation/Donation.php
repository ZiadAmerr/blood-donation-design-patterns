<?php
// File: Donation.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

abstract class Donation extends Model
{
    public int $id;
    public int $donor_id;
    public string $type; // e.g. "blood", "money"

    public function __construct(int $id = 0)
    {
        if ($id) {
            $row = $this->fetchSingle("SELECT * FROM Donation WHERE id = ?", "i", $id);
            if ($row) {
                $this->id       = (int) $row['id'];
                $this->type     = $row['type'];
                $this->donor_id = (int) $row['donor_id'];
            }
        }
    }

    // public static function create(int $donor_id, string $type): Donation
    // {
    //     $id = static::executeUpdate(
    //         "INSERT INTO Donation (donor_id, type) VALUES (?, ?)",
    //         "is",
    //         $donor_id,
    //         $type
    //     );
    //     return new static($id);
    // }

    // public function delete(): void
    // {
    //     static::executeUpdate(
    //         "DELETE FROM Donation WHERE id = ?",
    //         "i",
    //         $this->id
    //     );
    // }
    
}
