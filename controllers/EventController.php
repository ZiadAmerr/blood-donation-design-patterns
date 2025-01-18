<?php
// File: DonationController.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/DonationCampaign.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/OutreachEvent.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/FundraiserEvent.php';  
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/WorkshopEvent.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/Attendee.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/Activity.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Events/Organization.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class EventController extends Model
{
    // Get all donation campaigns from the database
    public function getDonationCampaigns(): array
    {
        $db = Database::getInstance(); 
        return DonationCampaign::fetchAllCampaigns($db);
    }

    public function createDonationCampaign(array $data): string {
        // Ensure the `id` is cast to an integer or set to null
        $campaign = new DonationCampaign(
            $data['name'],
            $data['description'],
            isset($data['id']) ? (int)$data['id'] : null
        );

        if ($campaign->save()) {
            return "Campaign created successfully!";
        } else {
            return "Failed to create the campaign.";
        }
    }

    public function createEvent(array $data): array
{
    // Check if city and country exist, otherwise create them
    $countryName = $data['country']; // Assuming country is passed in $data
    $cityName = $data['city']; // Assuming city is passed in $data

    // Create or fetch country address
    $countryAddressId = $this->createAddressIfNotExists($countryName);
    
    // Create or fetch city address as child of country
    $cityAddressId = $this->createAddressIfNotExists($cityName, $countryAddressId);

    // Get the donation campaign by ID
    $campaign = DonationCampaign::loadById($data['campaign_id']);

    if (!$campaign) {
        return ['success' => false, 'message' => "Campaign not found!"];
    }

    // Determine event type and create event accordingly
    $event = null;
    $eventID = 0; // Will be auto-generated
    $dateTime = new DateTime($data['datetime']);
    
    switch ($data['event_type']) {
        case 'outreach':
            $event = new OutreachEvent(
                $eventID, 
                $data['title'],
                $data['maxattendees'],
                $cityAddressId, // Use city address as event location
                $dateTime
            );
            break;
        case 'fundraiser':
            $event = new FundraiserEvent(
                $eventID,  // Event ID
                $data['title'],  // Event title
                $cityAddressId,  // City address ID
                $dateTime,  // Event date and time
                $data['goal_amount'],  // Goal amount for fundraising
                $data['raised_amount'] ?? 0.0  // Raised amount, default to 0.0 if not provided
            );
            break;
        case 'workshop':
            // Assuming $data['instructor_id'] contains the ID of the instructor (person_id)
            // Create the Volunteer object using the instructor's ID
            $instructor = new Volunteer($data['instructor_id']);  // This will use the person_id constructor and load volunteer details
            
            // Create a new WorkshopEvent with the necessary parameters
            $event = new WorkshopEvent(
                $eventID,           // Event ID
                $data['title'],     // Event title
                $data['max_attendees'], // Max attendees
                $dateTime,          // DateTime object for the event date and time
                $cityAddressId,     // City address ID (not Address object)
                [$instructor]       // Array of workshops (assuming just the instructor is passed here for simplicity)
            );
            break;
        default:
            return ['success' => false, 'message' => "Invalid event type!"];
    }

    // Link event to the campaign and save
    $campaign->addDonationComponent($event);
    $event->save();

    return ['success' => true, 'message' => "Event '{$event->getTitle()}' created successfully in campaign '{$campaign->getTitle()}'!"];
}

// Helper method to create address if not exists, and return the address ID
private function createAddressIfNotExists(string $name, ?int $parentId = null): int
{
    // Check if address already exists
    $address = self::fetchSingle(
        "SELECT id FROM " . 'addresses' . " WHERE name = ? AND parent_id = ?",
        "si",
        $name,
        $parentId
    );

    if ($address) {
        return $address['id']; // Return existing address ID
    }

    // Create new address if not found
    return Address::create($name, $parentId); // This will insert the new record and return the ID
}

    // Register an attendee for a specific event
    public function registerAttendee(array $data): array
    {
        $attendee = new Attendee(
            0, // ID will be auto-generated by the database
            $data['name'], // name
            $data['date_of_birth'], // date of birth
            $data['national_id'], // national ID
            $data['address_id'], // address ID
            $data['phone_number'] // phone number
        );
        
        // Now, save the attendee (e.g., using save method if it exists)
        $attendee->save();

        return ['success' => true, 'message' => "Attendee '{$attendee->getName()}' registered successfully!"];
    }

    // Add activity to an outreach event
    public function addActivityToEvent(int $eventId, array $data): array
    {
        $event = OutreachEvent::loadById($eventId);
        if ($event) {
            $event->addActivity($data['activity']); // Using addActivity method from OutreachEvent class
            $event->save(); // Save after adding the activity
            return ['success' => true, 'message' => "Activity '{$data['activity']}' added to event '{$event->getTitle()}' successfully!"];
        }
        return ['success' => false, 'message' => "Event not found!"];
    }

    // Add organization to an outreach event
    public function addOrganizationToEvent(int $eventId, array $data): array
    {
        $event = OutreachEvent::loadById($eventId);
        if ($event) {
            $event->addOrganization($data['organization']); // Using addOrganization method from OutreachEvent class
            $event->save(); // Save after adding the organization
            return ['success' => true, 'message' => "Organization '{$data['organization']}' added to event '{$event->getTitle()}' successfully!"];
        }
        return ['success' => false, 'message' => "Event not found!"];
    }

    // Record raised amount for a fundraiser event
    public function contributeToFundraiser(int $eventId, float $amount): array
    {
        $event = FundraiserEvent::loadById($eventId);
        if ($event) {
            $event->updateRaisedAmount($amount); // Using addRaisedAmount method from FundraiserEvent class
            $event->save(); // Save after updating the raised amount
            return ['success' => true, 'message' => "Contribution of $amount successfully recorded for event '{$event->getTitle()}'!"];
        }
        return ['success' => false, 'message' => "Event not found!"];
    }

    // Add a workshop to a workshop event
    public function addWorkshopToEvent(int $eventId, array $data): array
    {
        $event = WorkshopEvent::loadById($eventId);
        if ($event) {
            $event->addWorkshop($data['description'], $data['volunteer']); // Using addWorkshop method from WorkshopEvent class
            $event->save(); // Save after adding the workshop
            return ['success' => true, 'message' => "Workshop added to event '{$event->getTitle()}' successfully!"];
        }
        return ['success' => false, 'message' => "Event not found!"];
    }
}
?>
