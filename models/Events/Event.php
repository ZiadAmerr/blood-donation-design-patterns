<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/models/Events/Iterators/AttendeeIterator.php";

abstract class Event extends Model implements IDonationComponent
{
    protected int $eventID;
    protected string $title;
    protected int $maxAttendees;
    protected DateTime $dateTime;
    protected int $addressID; // Changed to store address_id (as per schema)
    protected array $attendees = [];
    protected array $volunteers = [];
    protected array $tickets = [];
    protected array $organizations = []; // New property to store organizations

    public function __construct(
        int $eventID,
        string $title,
        int $maxAttendees,
        DateTime $dateTime,
        int $addressID // Changed to accept address_id
    ) {
        $this->eventID = $eventID;
        $this->title = $title;
        $this->maxAttendees = $maxAttendees;
        $this->dateTime = $dateTime;
        $this->addressID = $addressID;
    }

    public function getTitle(): string {
        return $this->title;
    }
    public function getId(): int {
        return $this->eventID;
    }
    public function getGoalAmount(): float {
        return 0; 
    }
    public function getRaisedAmount(): float {
        return 0; 
    }
    public function showEventDetails(): string {
        return sprintf(
            "Event ID: %s\nTitle: %s\nMax Attendees: %d\nDate and Time: %s\nAddress ID: %d\n", 
            $this->eventID,
            $this->title,
            $this->maxAttendees,
            $this->dateTime->format('Y-m-d H:i:s'),
            $this->addressID // Changed to show address_id
        );
    }

    // Adding attendee while respecting maxAttendees limit
    public function addAttendee(Attendee $attendee): bool {
        if (count($this->attendees) < $this->maxAttendees) {
            $this->attendees[] = $attendee;
            return true;
        }
        return false; // Return false if the event is full
    }

    public function addVolunteer(Volunteer $volunteer): void {
        $this->volunteers[] = $volunteer;
    }

    public function addTicket(Ticket $ticket): void {
        $this->tickets[] = $ticket;
    }

    public function issueTicket(int $attendeeID): ?Ticket {
        // Check if max attendees reached before issuing a ticket
        if (count($this->attendees) >= $this->maxAttendees) {
            return null; // No ticket issued if max attendees reached
        }

        $ticket = new Ticket(uniqid(), $attendeeID, $this->eventID);
        $this->addTicket($ticket);
        return $ticket;
    }

    // Create attendee iterator for this event
    public function createAttendeeIterator(): AttendeeIterator {
        return new AttendeeIterator($this->attendees);
    }

    public function getAttendees(): array {
        return $this->attendees;
    }

    // Fetch all tickets for this event from the database
    // public function loadAllTickets(): array {
    //     $sql = "SELECT * FROM Ticket WHERE event_id = ?";
    //     $ticketsData = self::fetchAll($sql, "i", $this->eventID);
    //     return $ticketsData; 
    // }

    // Show attendee details using AttendeeIterator
    public function showAttendeeDetails(): string
    {
        $details = '';
        $iterator = $this->createAttendeeIterator(); // Use AttendeeIterator to iterate over attendees

        while ($iterator->hasNext()) {
            $attendee = $iterator->next();
            $details .= $attendee->showDetails() . "\n";
        }
        return $details;
    }

    // Remove an attendee from the event
    public function removeAttendee(Attendee $attendee): bool
    {
        foreach ($this->attendees as $index => $existingAttendee) {
            if ($existingAttendee === $attendee) {
                array_splice($this->attendees, $index, 1);
                return true;
            }
        }
        return false;
    }

    // New method to add an organization to the event
    public function addOrganization(Organization $organization): void {
        // Add the organization to the local array
        $this->organizations[] = $organization;

        // Insert the organization into the database (assuming there's a table called 'event_organizations')
        $this->insertOrganizationToDatabase($organization);
    }

    // Helper method to insert the organization into the database
    private function insertOrganizationToDatabase(Organization $organization): void {
        // Assuming `organization_id` is the ID for the organization
        $sql = "INSERT INTO event_organizations (event_id, organization_id) VALUES (?, ?)";
        $this->executeUpdate(
            $sql,
            "ii",
            $this->eventID,
            $organization->getId() // Assuming the Organization class has getId() method
        );
    }

    // Optional: You can also add a method to get all organizations associated with this event
    public function getOrganizations(): array {
        return $this->organizations;
    }
}
?>
