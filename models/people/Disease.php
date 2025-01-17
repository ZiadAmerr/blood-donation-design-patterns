<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Disease extends Model {
    public int $id;
    public string $name;
    public bool $prevents_donation;
    public const table_name = "diseases";

    public function __construct(int $id) {
        $row = $this->fetchSingle(
            "SELECT * FROM " . self::table_name . " WHERE id = ?",
            "i",
            $id
        );

        if ($row) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->prevents_donation = (bool) $row['prevents'];
        } else {
            throw new Exception("Disease with ID $id not found.");
        }
    }

    /**
     * Fetch all diseases from the database dynamically.
     * @return array List of diseases with ID, name, and prevents_donation status.
     */
    public static function getAllDiseases(): array {
        return static::fetchAll("SELECT id, name, prevents FROM " . self::table_name);
    }
}

?>
