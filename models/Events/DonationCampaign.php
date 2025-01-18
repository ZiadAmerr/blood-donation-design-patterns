<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/IterableEvent.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/IDonationComponent.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";

class DonationCampaign extends Model implements IDonationComponent, IIterableEvent {
    private array $components = [];  // Holds both Event and DonationCampaign instances
    private string $name;
    private string $description;
    private ?int $id;  // Allow null for optional ID

    public function __construct(string $name, string $description, ?int $id = null) {
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
    }


    public function save(): bool {
        $db = Database::getInstance();

        if ($this->id === null) {
            $query = "INSERT INTO donationcampaigns (name, description) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $db->error);
            }
            $stmt->bind_param("ss", $this->name, $this->description);
            $success = $stmt->execute();
            if ($success) {
                $this->id = $db->insert_id;
            }
            return $success;
        } else {
            $query = "UPDATE donationcampaigns SET name = ?, description = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $db->error);
            }
            $stmt->bind_param("ssi", $this->name, $this->description, $this->id);
            return $stmt->execute();
        }
    }
    public function getId(): ?int {
        return $this->id;
    }

    public static function loadById(int $id): ?DonationCampaign {
        $db = Database::getInstance();
        $query = "SELECT * FROM donationcampaigns WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $campaign = $result->fetch_assoc();

        if ($campaign) {
            return new DonationCampaign($campaign['name'], $campaign['description'], $campaign['id']);
        }
        return null;
    }

    public function deleteCampaign(): bool {
        if ($this->id !== null) {
            $db = Database::getInstance();
            $query = "DELETE FROM donationcampaigns WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("i", $this->id);
            return $stmt->execute();
        }
        return false;
    }
    public function getTitle(): string {
        return $this->name;
    }

    

    public static function fetchAllCampaigns(): array {
        $db = Database::getInstance();
        $sql = "SELECT * FROM donationcampaigns";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $campaigns = [];
        while ($row = $result->fetch_assoc()) {
            $campaigns[] = new self(
                $row['name'],
                $row['description'],
                $row['id']
            );
        }

        return $campaigns;
    }

    

    public function showEventDetails(): string {
        $details = '';
        $iterator = $this->createEventIterator();
        while ($iterator->hasNext()) {
            $component = $iterator->next();
            $details .= $component->showDetails() . "\n";
        }
        return $details;
    }

    public function addDonationComponent(IDonationComponent $component): void {
        $this->components[] = $component;
    }

    public function createEventIterator(): EventIterator {
        return new EventIterator($this->components);
    }
    public function getEvents(): array {
        return array_filter($this->components, function ($component) {
            return $component instanceof Event;
        });
    }
    public function getDescription(): string {
        return $this->description;
    }
    public function getDonationComponents(): array {
        return $this->components;
    }
}
?>
