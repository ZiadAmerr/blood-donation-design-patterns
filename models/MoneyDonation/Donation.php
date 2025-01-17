<?php
// File: Donation.php
require_once __DIR__ . '/../services/database_service.php';

abstract class Donation extends Model
{
    public int $id;
    public int $donor_id;
    public string $type; // e.g., "blood", "money"
    public DateTime $date;

    public function __construct(int $id = 0)
    {
        if ($id) {
            $row = $this->fetchSingle("SELECT * FROM Donation WHERE id = ?", "i", $id);
            if ($row) {
                $this->id       = (int) $row['id'];
                $this->type     = $row['type'];
                $this->donor_id = (int) $row['donor_id'];
                $this->date     = new DateTime($row['date']);
            }
        }
    }

    public static function create(int $donor_id, string $type): Donation
    {
        $id = static::executeUpdate(
            "INSERT INTO Donation (donor_id, type, date) VALUES (?, ?, NOW())",
            "is",
            $donor_id,
            $type,
        );
        return new static($id);
    }

    public function delete(): void
    {
        static::executeUpdate(
            "DELETE FROM Donation WHERE id = ?",
            "i",
            $this->id
        );
    }

    public function getFormattedDate(string $format = "Y-m-d H:i:s"): string
    {
        return $this->date->format($format);
    }
}
