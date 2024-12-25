<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

abstract class Event extends Model { 
    protected int $id;
    protected string $title;
    protected Address $address;
    protected DateTime $dateTime;

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime) {
        $this->id = $id;
        $this->title = $title;
        $this->address = $address;
        $this->dateTime = $dateTime;
    }

    abstract public static function create(array $data): Event;
    abstract public function update(array $data): void;
    abstract public function delete(): void;
    abstract public function load(): void;
    abstract protected function getDetails(): string;

    protected function fetchEventById(int $id): ?array {
        $sql = "SELECT * FROM Event WHERE id = ?";
        return $this->fetchSingle($sql, "i", $id);
    }

    protected function updateEventData(string $title, int $addressId, DateTime $dateTime): void {
        $sql = "UPDATE Event SET title = ?, address_id = ?, date_time = ? WHERE id = ?";
        $this->executeUpdate($sql, "sisdi", $title, $addressId, $dateTime->format('Y-m-d H:i:s'), $this->id);
    }
}
?>
