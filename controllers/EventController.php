<?php
require_once __DIR__ . '/../models/events/EventFactory.php';
require_once __DIR__ . '/../models/events/FundRaiserEventFactory.php';
require_once __DIR__ . '/../models/events/OutreachEventFactory.php';
require_once __DIR__ . '/../models/events/WorkShopEventFactory.php';

class EventController
{
    public function handleRequest(): void
    {
        $action = $_GET['action'] ?? 'create_event_form';

        switch ($action) {
            case 'create':
                $this->createEvent();
                break;

            default:
                $this->showEventForm();
                break;
        }
    }

    // Show a simple form for creating an event
    private function showEventForm(): void
    {
?>
        <h1>Create an Event</h1>
        <form method="POST" action="?action=create">
            <label>Event Type:</label>
            <select name="event_type">
                <option value="fundraiser">Fundraiser</option>
                <option value="outreach">Outreach</option>
                <option value="workshop">Workshop</option>
            </select><br><br>

            <label>Title:</label>
            <input type="text" name="title" required><br><br>

            <label>Date & Time:</label>
            <input type="datetime-local" name="date_time" required><br><br>

            <label>Address ID:</label>
            <input type="number" name="address_id" placeholder="1"><br><br>

            <label>Goal Amount (if Fundraiser):</label>
            <input type="number" name="goal_amount" step="0.01"><br><br>

            <!-- Add more fields if needed for outreach or workshop -->

            <button type="submit">Create</button>
        </form>
<?php
    }

    // Create the event in the database
    private function createEvent(): void
    {
        // Usually you'd verify POST data carefully
        $eventType = $_POST['event_type'] ?? 'fundraiser';
        $title = $_POST['title'] ?? 'Untitled';
        $dateTime = $_POST['date_time'] ?? date('Y-m-d H:i:s');
        $addressId = (int)($_POST['address_id'] ?? 1);

        // You can parse the date string into a DateTime object
        $dt = new DateTime($dateTime);

        // Additional fields for fundraiser:
        $goalAmount = (float)($_POST['goal_amount'] ?? 0);

        // Prepare the data array we’ll pass into the factory
        $data = [
            'title'       => $title,
            'address_id'  => $addressId,
            'dateTime'    => $dt,
            'goalAmount'  => $goalAmount,
            // ... etc.
        ];

        // Choose a factory based on the event type
        switch ($eventType) {
            case 'fundraiser':
                $factory = new FundRaiserEventFactory();
                break;
            case 'outreach':
                $factory = new OutreachEventFactory();
                break;
            case 'workshop':
                $factory = new WorkShopEventFactory();
                break;
            default:
                echo "Invalid event type";
                return;
        }

        try {
            // Use the factory to create the event object
            $event = $factory->createEvent($data);

            // Because we have static methods like `FundraiserEvent::create($data)`,
            // we might not need to call $event->create(...) again. 
            // But if your code does that, do it here.
            //
            // e.g., if `createEvent(...)` does NOT insert into DB automatically, you can do:
            // $event->create($data);

            echo "Event created successfully!";
        } catch (Exception $e) {
            echo "Failed to create event: " . $e->getMessage();
        }
    }
}
