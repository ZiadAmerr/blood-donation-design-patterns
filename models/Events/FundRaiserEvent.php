<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/people/Address.php";


class FundraiserEvent extends Event {
    private float $goalAmount;
    private float $raisedAmount;

    public function __construct(int $id, string $title, int $addressID, DateTime $dateTime, float $goalAmount, float $raisedAmount = 0.0) {
        parent::__construct($id, $title, $addressID, $dateTime);
        $this->goalAmount = $goalAmount;
        $this->raisedAmount = $raisedAmount;
    }

    // Save method for inserting or updating the fundraiser event
    public function save(): bool
    {
        if ($this->eventID === 0) {
            // Insert new fundraiser event into the database
            $sql = "INSERT INTO FundraiserEvent (title, address_id, date_time, goal_amount) VALUES (?, ?, ?, ?)";
            $fundraiserId = $this->executeUpdate(
                $sql,
                "sisd",
                $this->title,
                $this->addressID,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->goalAmount
            );

            $this->eventID = $fundraiserId;  // Set the event ID after insertion
            return true;
        } else {
            // Update existing fundraiser event
            $sql = "UPDATE FundraiserEvent SET title = ?, address_id = ?, date_time = ?, goal_amount = ? WHERE id = ?";
            $this->executeUpdate(
                $sql,
                "sisdi",
                $this->title,
                $this->addressID,
                $this->dateTime->format('Y-m-d H:i:s'),
                $this->goalAmount,
                $this->eventID
            );

            return true;
        }
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
            $data['address_id'],  
            new DateTime($data['dateTime']),
            $data['goalAmount']
        );

        // Save the new fundraiser event to the database
        $fundraiser->save();
        
        return $fundraiser;
    }

    public function updateEvent(array $data): void {
        $this->title = $data['title'];
        $this->addressID = $data['address_id']; 
        $this->dateTime = new DateTime($data['dateTime']);
        $this->goalAmount = $data['goalAmount'];

        $this->save();  // Save the updated event
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
            $this->addressID = $row['address_id']; 
            $this->dateTime = new DateTime($row['date_time']);
            $this->goalAmount = (float)$row['goal_amount'];
            $this->raisedAmount = (float)($row['raised_amount'] ?? 0.0);
        }
    }

    // New loadById method
    public static function loadById(int $id): ?FundraiserEvent {
        $sql = "SELECT * FROM FundraiserEvent WHERE id = ?";
        $row = self::fetchSingle($sql, "i", $id);

        if ($row) {
            return new self(
                (int) $row['id'],
                $row['title'],
                (int) $row['address_id'],
                new DateTime($row['date_time']),
                (float) $row['goal_amount'],
                (float)($row['raised_amount'] ?? 0.0)
            );
        }
        return null;  // Return null if no event is found
    }

    protected function getDetails(): string {
        return "Fundraiser Event: {$this->title}, Goal: {$this->goalAmount}, Raised: {$this->raisedAmount}";
    }
}
?>
