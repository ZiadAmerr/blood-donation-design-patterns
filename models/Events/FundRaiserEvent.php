<?php
require_once "event.php";

class FundraiserEvent extends Event {
    private float $goalAmount;
    private float $raisedAmount;

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, float $goalAmount, float $raisedAmount = 0.0) {
        parent::__construct($id, $title, $address, $dateTime);
        $this->goalAmount = $goalAmount;
        $this->raisedAmount = $raisedAmount;
    }

    public static function create(array $data): FundraiserEvent {
        // Initialize FundraiserEvent instance
        $fundraiser = new self(0, $data['title'], new Address($data['address_id']), new DateTime($data['dateTime']), $data['goalAmount']);

        // Insert into database
        $sql = "INSERT INTO FundraiserEvent (title, address_id, date_time, goal_amount) VALUES (?, ?, ?, ?)";
        $fundraiserId = $fundraiser->executeUpdate($sql, "sisd", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['goalAmount']);
        $fundraiser->id = $fundraiserId;  

        return $fundraiser;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->goalAmount = $data['goalAmount'];

        $sql = "UPDATE FundraiserEvent SET title = ?, address_id = ?, date_time = ?, goal_amount = ? WHERE id = ?";
        $this->executeUpdate($sql, "sisdi", $data['title'], $data['address_id'], $data['dateTime']->format('Y-m-d H:i:s'), $data['goalAmount'], $this->id);
    }

    public function delete(): void {
        $sql = "DELETE FROM FundraiserEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->id);
    }

    public function load(): void {
        $sql = "SELECT * FROM FundraiserEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->id);

        if ($row) {
            $this->title = $row['title'];
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->goalAmount = (float)$row['goal_amount'];
        }
    }

    protected function getDetails(): string {
        return "Fundraiser Event: {$this->title}, Goal: {$this->goalAmount}, Raised: {$this->raisedAmount}";
    }
}
?>
