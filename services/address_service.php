<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";

class AddressService extends Model {
    public static function getAllAddresses(): array {
        return static::fetchAll(
            "SELECT id, name, parent_id FROM Address"
        );
    }
}

?>

