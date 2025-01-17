<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/IterableEvent.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/IDonationComponent.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Event.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/DonationCampaign.php";  // Import the DonationCampaign class

class DonationCampaign implements IDonationComponent, IIterableEvent {
    private array $components = [];  // Holds both Event and DonationCampaign instances
    private string $name;
    private string $description;
    private mysqli $db;
    private int $id;

    public function __construct($db, $name, $description, $id = null) {
        $this->db = Database::getInstance();
        $this->name = $name;
        $this->description = $description;
        $this->id = $id;
    }

    public function getTitle(): string {
        return $this->name;
    }

    // Create a new donation campaign in the database or update if ID exists
    public function save(): bool {
        if ($this->id === null) {
            // Insert new campaign
            $query = "INSERT INTO donation_campaigns (name, description) VALUES (:name, :description)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':name' => $this->name,
                ':description' => $this->description
            ]);
        } else {
            // Update existing campaign
            $query = "UPDATE donation_campaigns SET name = :name, description = :description WHERE id = :id";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':name' => $this->name,
                ':description' => $this->description,
                ':id' => $this->id
            ]);
        }
    }

    // Static method to load a donation campaign from the database by ID
    public static function loadById($id): ?DonationCampaign {
        $db = Database::getInstance();
        $query = "SELECT * FROM donation_campaigns WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);

        // Fetch campaign data and return a new instance if found
        $campaign = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($campaign) {
            $donationCampaign = new DonationCampaign($db, $campaign['name'], $campaign['description'], $campaign['id']);
            return $donationCampaign;
        }
        return null;  // Return null if campaign not found
    }

    // Delete a donation campaign from the database
    public function deleteCampaign($id): bool {
        $query = "DELETE FROM donation_campaigns WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }

    public static function fetchAllCampaigns($db): array {
        $query = "SELECT * FROM donation_campaigns";
        $stmt = $db->prepare($query);
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        // Fetch all rows as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Show event details
    public function showEventDetails(): string {
        $details = '';
        $iterator = $this->createEventIterator();
        while ($iterator->hasNext()) {
            $component = $iterator->next();
            $details .= $component->showDetails() . "\n";
        }
        return $details;
    }

    // Show attendee details for all events in the campaign
    public function showAttendeeDetails(): string {
        $details = '';
        $iterator = $this->createEventIterator();
        while ($iterator->hasNext()) {
            $component = $iterator->next();
            $details .= $component->showAttendeeDetails() . "\n";
        }
        return $details;
    }

    // Add a donation component (either Event or DonationCampaign)
    public function addDonationComponent(IDonationComponent $component): void {
        $this->components[] = $component;
    }

    // Create event iterator for the components (both Event and DonationCampaign)
    public function createEventIterator(): EventIterator {
        return new EventIterator($this->components);
    }
}
?>
