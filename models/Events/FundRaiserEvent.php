<?php
require_once "Event.php";
require_once "Address.php";

class FundraiserEvent extends Event {
    private float $goalAmount;
    private float $raisedAmount;

    public function __construct(int $id, string $title, Address $address, DateTime $dateTime, float $goalAmount, float $raisedAmount = 0.0) {
        parent::__construct($id, $title, $address, $dateTime);
        $this->goalAmount = $goalAmount;
        $this->raisedAmount = $raisedAmount;
    }
    public function updateRaisedAmount(float $amount): void {
        $this->raisedAmount += $amount;

        $sql = "UPDATE FundraiserEvent SET raised_amount = ? WHERE id = ?";
        $this->executeUpdate($sql, "di", $this->raisedAmount, $this->eventID);
    }

    public static function create(array $data): FundraiserEvent {
        $fundraiser = new self(
            0,
            $data['title'],
            new Address($data['address_id']),
            new DateTime($data['dateTime']),
            $data['goalAmount']
        );

        $sql = "INSERT INTO FundraiserEvent (title, address_id, date_time, goal_amount) VALUES (?, ?, ?, ?)";
        $fundraiserId = $fundraiser->executeUpdate(
            $sql,
            "sisd",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['goalAmount']
        );
        $fundraiser->eventID = $fundraiserId;

        return $fundraiser;
    }

    public function update(array $data): void {
        $this->title = $data['title'];
        $this->address = new Address($data['address_id']);
        $this->dateTime = new DateTime($data['dateTime']);
        $this->goalAmount = $data['goalAmount'];

        $sql = "UPDATE FundraiserEvent SET title = ?, address_id = ?, date_time = ?, goal_amount = ? WHERE id = ?";
        $this->executeUpdate(
            $sql,
            "sisdi",
            $data['title'],
            $data['address_id'],
            $data['dateTime']->format('Y-m-d H:i:s'),
            $data['goalAmount'],
            $this->eventID
        );
    }

    public function delete(): void {
        $sql = "DELETE FROM FundraiserEvent WHERE id = ?";
        $this->executeUpdate($sql, "i", $this->eventID);
    }

    public function load(): void {
        $sql = "SELECT * FROM FundraiserEvent WHERE id = ?";
        $row = $this->fetchSingle($sql, "i", $this->eventID);

        if ($row) {
            $this->title = $row['title'];
            $this->address = new Address($row['address_id']);
            $this->dateTime = new DateTime($row['date_time']);
            $this->goalAmount = (float)$row['goal_amount'];
            $this->raisedAmount = (float)$row['raised_amount'] ?? 0.0;
        }
    }

    protected function getDetails(): string {
        return "Fundraiser Event: {$this->title}, Goal: {$this->goalAmount}, Raised: {$this->raisedAmount}";
    }
}
?>
