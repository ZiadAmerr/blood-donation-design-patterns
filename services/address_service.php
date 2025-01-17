<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";

class AddressService extends Model {
    public static function getAllAddresses(): array {
        return static::fetchAll(
            "SELECT id, name, parent_id FROM " . Address::table_name
        );
    }

    public static function getOrCreateAddress($new_address_name, $parent_address_id, $selected_address) {
        if (!empty($new_address_name)) {
            // Create a new address and return the ID
            return Address::create($new_address_name, $parent_address_id);
        } elseif (!empty($selected_address)) {
            // Use an existing address
            return (int)$selected_address;
        } else {
            throw new Exception("Please choose or create an address.");
        }
    }
}

?>

