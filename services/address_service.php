<?php
require_once __DIR__ . '/../models/models.php';

class AddressService extends Model {
    public static function getAllAddresses(): array {
        return static::fetchAll(
            "SELECT id, name, parent_id FROM Address"
        );
    }
}

?>

