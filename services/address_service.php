<?php
require_once __DIR__ . '/../models/models.php';

class AddressService {
    // Fetch all addresses as an array
    public static function getAllAddresses(): array {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT id, name, parent_id FROM Address");
        $query->execute();
        $result = $query->get_result();

        $addresses = [];
        while ($row = $result->fetch_assoc()) {
            $addresses[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'parent_id' => $row['parent_id']
            ];
        }
        return $addresses;
    }
}
?>

