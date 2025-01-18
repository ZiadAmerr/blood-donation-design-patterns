<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class BloodDonationModel extends Model
{
    // Fetch all donations from the database
    public static function getAllDonations(): array
    {
        $sql = "SELECT * FROM blooddonation";
        return self::fetchAll($sql);
    }

    // Fetch a specific donation by ID
    public static function getDonationById(int $id): ?array
    {
        $sql = "SELECT * FROM blooddonation WHERE id = ?";
        return self::fetchSingle($sql, "i", $id);
    }

    // Update a specific donation record
    // Update a specific donation record with multiple fields
public static function updateDonation(int $id, string $name, string $donationType, string $status): bool
{
    $sql = "UPDATE blooddonation SET name = ?, donation_type = ?, status = ? WHERE id = ?";
    return self::executeUpdate($sql, "sssi", $name, $donationType, $status, $id) > 0;
}


    // Delete a donation record
    public static function deleteDonation(int $id): bool
    {
        $sql = "DELETE FROM blooddonation WHERE id = ?";
        $result = self::executeUpdate($sql, "i", $id);
        return $result > 0;
    }
}
?>
