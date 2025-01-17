<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

// Address (extends Model for DB operations)
class Address extends Model
{
    public int $id;
    public string $name;
    public ?int $parent_id;
    public const table_name = "addresses";

    // Constructor
    public function __construct(int $id) {

        $data = self::fetchSingle(
            "SELECT * FROM " . self::table_name . " WHERE id = ?",
            "i",
            $id
        );

        if (!$data) {
            throw new Exception("Address with ID $id not found.");
        }

    
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->parent_id = $data['parent_id'] !== null ? (int) $data['parent_id'] : null;
    }

    // Static method to create a new Address
    public static function create(string $name, ?int $parent_id = null): int
    {

        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        $id = self::executeUpdate(
            "INSERT INTO " . self::table_name . " (name, parent_id) VALUES (?, ?)",
            "si",
            $name,
            $parent_id
        );

        return $id;
    }

    // Method to update an existing Address
    public function update(string $name, ?int $parent_id = null): void
    {
        if ($parent_id !== null) {
            self::validateParentId($parent_id);
        }

        self::executeUpdate(
            "UPDATE " . self::table_name . " SET name = ?, parent_id = ? WHERE id = ?",
            "sii",
            $name,
            $parent_id,
            $this->id
        );

        $this->name = $name;
        $this->parent_id = $parent_id;
    }

    // Method to delete an Address
    public function delete(): void
    {
        self::executeUpdate(
            "DELETE FROM " . self::table_name . " WHERE id = ?",
            "i",
            $this->id
        );
    }

    // Static method to validate if a parent_id exists in the database
    private static function validateParentId(int $parent_id): void
    {
        $exists = self::fetchSingle(
            "SELECT id FROM " . self::table_name . " WHERE id = ?",
            "i",
            $parent_id
        );

        if (!$exists) {
            throw new Exception("Parent Address with ID $parent_id does not exist.");
        }
    }

    // Helper to fetch the parent Address as an object (if applicable)
    public function getParent(): ?Address
    {
        return $this->parent_id !== null ? new Address($this->parent_id) : null;
    }
    // Method to convert Address object to string
    public function __toString(): string
    {
        return "Address ID: $this->id, Name: $this->name" . ($this->parent_id !== null ? ", Parent ID: $this->parent_id" : "");
    }
}

?>