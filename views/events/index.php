<?php
// File: event_view.php

// Include necessary controller and model files
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/EventController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Volunteer/Volunteer.php'; // Assuming Volunteer model exists

// Start the session to handle messages
session_name('events');
session_start();


$eventController = new EventController();

// Initialize message variables
$successMessage = '';
$errorMessage = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine which form was submitted
    echo 'test 0';
    if (isset($_POST['create_campaign'])) {
        // Handle creation of a new donation campaign
        echo 'test 1';
        $data = [
            'name' => $_POST['campaign_name'],
            'description' => $_POST['campaign_description'],
            'start_date' => $_POST['campaign_start_date'],
            'end_date' => $_POST['campaign_end_date'],
            'target_amount' => $_POST['campaign_target_amount']
        ];
        echo 'test 2';

        $result = $eventController->createDonationCampaign($data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
        echo 'test 3';

    } elseif (isset($_POST['create_event'])) {
        // Handle creation of a new event
        $data = [
            'campaign_id' => $_POST['event_campaign_id'],
            'event_type' => $_POST['event_type'],
            'title' => $_POST['event_title'],
            'max_attendees' => $_POST['event_max_attendees'],
            'address_id' => $_POST['event_address_id'],
            'date_time' => $_POST['event_date_time'],
            // Additional fields based on event type
            'goal_amount' => $_POST['event_goal_amount'] ?? null,
            'raised_amount' => $_POST['event_raised_amount'] ?? null,
            'instructor_id' => $_POST['event_instructor_id'] ?? null,
            'description' => $_POST['workshop_description'] ?? null,
            'volunteer_id' => $_POST['workshop_volunteer_id'] ?? null
        ];
        $result = $eventController->createEvent($data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    } elseif (isset($_POST['register_attendee'])) {
        // Handle attendee registration
        $data = [
            'name' => $_POST['attendee_name'],
            'date_of_birth' => $_POST['attendee_dob'],
            'national_id' => $_POST['attendee_national_id'],
            'address_id' => $_POST['attendee_address_id'],
            'phone_number' => $_POST['attendee_phone_number']
        ];
        $result = $eventController->registerAttendee($data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    } elseif (isset($_POST['add_activity'])) {
        // Handle adding activity to outreach event
        $eventId = $_POST['activity_event_id'];
        $data = [
            'activity' => $_POST['activity_description']
        ];
        $result = $eventController->addActivityToEvent($eventId, $data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    } elseif (isset($_POST['add_organization'])) {
        // Handle adding organization to outreach event
        $eventId = $_POST['organization_event_id'];
        $data = [
            'organization' => $_POST['organization_name']
        ];
        $result = $eventController->addOrganizationToEvent($eventId, $data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    } elseif (isset($_POST['contribute_fundraiser'])) {
        // Handle contribution to fundraiser event
        $eventId = $_POST['fundraiser_event_id'];
        $amount = floatval($_POST['contribution_amount']);
        $result = $eventController->contributeToFundraiser($eventId, $amount);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    } elseif (isset($_POST['add_workshop'])) {
        // Handle adding workshop to workshop event
        $eventId = $_POST['workshop_event_id'];
        $data = [
            'description' => $_POST['workshop_description'],
            'volunteer' => $_POST['workshop_volunteer_id']
        ];
        $result = $eventController->addWorkshopToEvent($eventId, $data);
        if ($result['success']) {
            $successMessage = $result['message'];
        } else {
            $errorMessage = $result['message'];
        }
    }
    
    // Store messages in session to display after redirect
    $_SESSION['success'] = $successMessage;
    $_SESSION['error'] = $errorMessage;
    
    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve messages from session
if (isset($_SESSION['success'])) {
    $successMessage = $_SESSION['success'];
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    $errorMessage = $_SESSION['error'];
    unset($_SESSION['error']);
}

// Fetch all donation campaigns
$campaigns = $eventController->getDonationCampaigns();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Campaigns and Events Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        .container {
            width: 95%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
        }
        h2 {
            color: #333;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
        }
        .success {
            background-color: #e0ffe0;
            border: 1px solid #00b300;
        }
        .error {
            background-color: #ffe0e0;
            border: 1px solid #b30000;
        }
        form {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid #ccc;
            background-color: #fafafa;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="number"], input[type="date"], input[type="datetime-local"], textarea, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        input[type="submit"] {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007BFF;
            border: none;
            color: #fff;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .campaign, .event {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #fafafa;
        }
        .event-list {
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Donation Campaigns and Events Management</h1>

        <!-- Display success or error messages -->
        <?php if ($successMessage): ?>
            <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <!-- Form to Create a New Donation Campaign -->
        <h2>Create New Donation Campaign</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"> 
            <label for="campaign_name">Campaign Name:</label>
            <input type="text" id="campaign_name" name="campaign_name" required>

            <label for="campaign_description">Description:</label>
            <textarea id="campaign_description" name="campaign_description" rows="4" required></textarea>

            <label for="campaign_start_date">Start Date:</label>
            <input type="date" id="campaign_start_date" name="campaign_start_date" required>

            <label for="campaign_end_date">End Date:</label>
            <input type="date" id="campaign_end_date" name="campaign_end_date" required>

            <label for="campaign_target_amount">Target Amount:</label>
            <input type="number" id="campaign_target_amount" name="campaign_target_amount" step="0.01" required>

            <input type="submit" name="create_campaign" value="Create Campaign">
        </form>

        <!-- Form to Create a New Event -->
        <h2>Create New Event</h2>
        <form method="POST" action="">
            <label for="event_campaign_id">Select Donation Campaign:</label>
            <select id="event_campaign_id" name="event_campaign_id" required>
                <option value="">-- Select Campaign --</option>
                <?php foreach ($campaigns as $campaign): ?>
                    <option value="<?php echo $campaign->getId(); ?>">
                        <?php echo htmlspecialchars($campaign->getTitle()); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="event_type">Event Type:</label>
            <select id="event_type" name="event_type" required onchange="toggleEventTypeFields()">
                <option value="">-- Select Event Type --</option>
                <option value="outreach">Outreach Event</option>
                <option value="fundraiser">Fundraiser Event</option>
                <option value="workshop">Workshop Event</option>
            </select>

            <div id="common_event_fields" style="display:none;">
                <label for="event_title">Event Title:</label>
                <input type="text" id="event_title" name="event_title" required>

                <label for="event_max_attendees">Max Attendees:</label>
                <input type="number" id="event_max_attendees" name="event_max_attendees" required>

                <label for="event_address_id">Address ID:</label>
                <input type="number" id="event_address_id" name="event_address_id" required>

                <label for="event_date_time">Date and Time:</label>
                <input type="datetime-local" id="event_date_time" name="event_date_time" required>
            </div>

            <!-- Additional fields for Fundraiser Event -->
            <div id="fundraiser_fields" style="display:none;">
                <label for="event_goal_amount">Goal Amount:</label>
                <input type="number" id="event_goal_amount" name="event_goal_amount" step="0.01">

                <label for="event_raised_amount">Raised Amount:</label>
                <input type="number" id="event_raised_amount" name="event_raised_amount" step="0.01" value="0.00">
            </div>

            <!-- Additional fields for Workshop Event -->
            <div id="workshop_fields" style="display:none;">
                <label for="event_instructor_id">Instructor ID:</label>
                <input type="number" id="event_instructor_id" name="event_instructor_id">

                <label for="workshop_description">Workshop Description:</label>
                <textarea id="workshop_description" name="workshop_description" rows="3"></textarea>

                <label for="workshop_volunteer_id">Volunteer ID:</label>
                <input type="number" id="workshop_volunteer_id" name="workshop_volunteer_id">
            </div>

            <input type="submit" name="create_event" value="Create Event">
        </form>

        <!-- Form to Register an Attendee -->
        <h2>Register Attendee</h2>
        <form method="POST" action="">
            <label for="attendee_name">Name:</label>
            <input type="text" id="attendee_name" name="attendee_name" required>

            <label for="attendee_dob">Date of Birth:</label>
            <input type="date" id="attendee_dob" name="attendee_dob" required>

            <label for="attendee_national_id">National ID:</label>
            <input type="text" id="attendee_national_id" name="attendee_national_id" required>

            <label for="attendee_address_id">Address ID:</label>
            <input type="number" id="attendee_address_id" name="attendee_address_id" required>

            <label for="attendee_phone_number">Phone Number:</label>
            <input type="text" id="attendee_phone_number" name="attendee_phone_number" required>

            <input type="submit" name="register_attendee" value="Register Attendee">
        </form>

        <!-- Form to Add Activity to Outreach Event -->
        <h2>Add Activity to Outreach Event</h2>
        <form method="POST" action="">
            <label for="activity_event_id">Select Outreach Event:</label>
            <select id="activity_event_id" name="activity_event_id" required>
                <option value="">-- Select Outreach Event --</option>
                <?php
                // Fetch outreach events from campaigns
                foreach ($campaigns as $campaign):
                    foreach ($campaign->getEvents() as $event):
                        if ($event instanceof OutreachEvent):
                ?>
                            <option value="<?php echo $event->getId(); ?>">
                                <?php echo htmlspecialchars($campaign->getTitle() . ' - ' . $event->getTitle()); ?>
                            </option>
                <?php
                        endif;
                    endforeach;
                endforeach;
                ?>
            </select>

            <label for="activity_description">Activity Description:</label>
            <textarea id="activity_description" name="activity_description" rows="3" required></textarea>

            <input type="submit" name="add_activity" value="Add Activity">
        </form>

        <!-- Form to Add Organization to Outreach Event -->
        <h2>Add Organization to Outreach Event</h2>
        <form method="POST" action="">
            <label for="organization_event_id">Select Outreach Event:</label>
            <select id="organization_event_id" name="organization_event_id" required>
                <option value="">-- Select Outreach Event --</option>
                <?php
                // Fetch outreach events from campaigns
                foreach ($campaigns as $campaign):
                    foreach ($campaign->getEvents() as $event):
                        if ($event instanceof OutreachEvent):
                ?>
                            <option value="<?php echo $event->getId(); ?>">
                                <?php echo htmlspecialchars($campaign->getTitle() . ' - ' . $event->getTitle()); ?>
                            </option>
                <?php
                        endif;
                    endforeach;
                endforeach;
                ?>
            </select>

            <label for="organization_name">Organization Name:</label>
            <input type="text" id="organization_name" name="organization_name" required>

            <input type="submit" name="add_organization" value="Add Organization">
        </form>

        <!-- Form to Contribute to Fundraiser Event -->
        <h2>Contribute to Fundraiser Event</h2>
        <form method="POST" action="">
            <label for="fundraiser_event_id">Select Fundraiser Event:</label>
            <select id="fundraiser_event_id" name="fundraiser_event_id" required>
                <option value="">-- Select Fundraiser Event --</option>
                <?php
                // Fetch fundraiser events from campaigns
                foreach ($campaigns as $campaign):
                    foreach ($campaign->getEvents() as $event):
                        if ($event instanceof FundraiserEvent):
                ?>
                            <option value="<?php echo $event->getId(); ?>">
                                <?php echo htmlspecialchars($campaign->getTitle() . ' - ' . $event->getTitle()); ?>
                            </option>
                <?php
                        endif;
                    endforeach;
                endforeach;
                ?>
            </select>

            <label for="contribution_amount">Contribution Amount:</label>
            <input type="number" id="contribution_amount" name="contribution_amount" step="0.01" required>

            <input type="submit" name="contribute_fundraiser" value="Contribute">
        </form>

        <!-- Form to Add Workshop to Workshop Event -->
        <h2>Add Workshop to Workshop Event</h2>
        <form method="POST" action="">
            <label for="workshop_event_id">Select Workshop Event:</label>
            <select id="workshop_event_id" name="workshop_event_id" required>
                <option value="">-- Select Workshop Event --</option>
                <?php
                // Fetch workshop events from campaigns
                foreach ($campaigns as $campaign):
                    foreach ($campaign->getEvents() as $event):
                        if ($event instanceof WorkshopEvent):
                ?>
                            <option value="<?php echo $event->getId(); ?>">
                                <?php echo htmlspecialchars($campaign->getTitle() . ' - ' . $event->getTitle()); ?>
                            </option>
                <?php
                        endif;
                    endforeach;
                endforeach;
                ?>
            </select>

            <label for="workshop_description">Workshop Description:</label>
            <textarea id="workshop_description" name="workshop_description" rows="3" required></textarea>

            <label for="workshop_volunteer_id">Assign Volunteer ID:</label>
            <input type="number" id="workshop_volunteer_id" name="workshop_volunteer_id" required>

            <input type="submit" name="add_workshop" value="Add Workshop">
        </form>

        <!-- Display Existing Campaigns and Events -->
        <h2>Existing Donation Campaigns and Events</h2>
        <?php if (empty($campaigns)): ?>
            <p>No donation campaigns found.</p>
        <?php else: ?>
            <?php foreach ($campaigns as $campaign): ?>
                <div class="campaign">
                    <h3><?php echo htmlspecialchars($campaign->getTitle()); ?></h3>
                    <p><?php echo htmlspecialchars($campaign->getDescription()); ?></p>
                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($campaign->getStartDate()->format('Y-m-d')); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($campaign->getEndDate()->format('Y-m-d')); ?></p>
                    <p><strong>Target Amount:</strong> $<?php echo htmlspecialchars(number_format($campaign->getTargetAmount(), 2)); ?></p>

                    <!-- List of Events under this Campaign -->
                    <div class="event-list">
                        <h4>Events:</h4>
                        <?php
                        $events = $campaign->getEvents();
                        if (empty($events)):
                        ?>
                            <p>No events under this campaign.</p>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <div class="event">
                                    <h5><?php echo htmlspecialchars($event->getTitle()); ?> (<?php echo htmlspecialchars(get_class($event)); ?>)</h5>
                                    <p><strong>Max Attendees:</strong> <?php echo htmlspecialchars($event->getMaxAttendees()); ?></p>
                                    <p><strong>Date and Time:</strong> <?php echo htmlspecialchars($event->getDateTime()->format('Y-m-d H:i:s')); ?></p>
                                    <p><strong>Address ID:</strong> <?php echo htmlspecialchars($event->getAddressID()); ?></p>

                                    <!-- Specific Details Based on Event Type -->
                                    <?php if ($event instanceof FundraiserEvent): ?>
                                        <p><strong>Goal Amount:</strong> $<?php echo htmlspecialchars(number_format($event->getGoalAmount(), 2)); ?></p>
                                        <p><strong>Raised Amount:</strong> $<?php echo htmlspecialchars(number_format($event->getRaisedAmount(), 2)); ?></p>
                                    <?php elseif ($event instanceof WorkshopEvent): ?>
                                        <p><strong>Workshops:</strong></p>
                                        <ul>
                                            <?php foreach ($event->getWorkshops() as $workshop): ?>
                                                <li>
                                                    <strong>Description:</strong> <?php echo htmlspecialchars($workshop['description']); ?>,
                                                    <strong>Volunteer ID:</strong> <?php echo htmlspecialchars($workshop['volunteer_id']); ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php elseif ($event instanceof OutreachEvent): ?>
                                        <p><strong>Activities:</strong></p>
                                        <ul>
                                            <?php foreach ($event->getActivities() as $activity): ?>
                                                <li><?php echo htmlspecialchars($activity); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <p><strong>Organizations:</strong></p>
                                        <ul>
                                            <?php foreach ($event->getOrganizations() as $organization): ?>
                                                <li><?php echo htmlspecialchars($organization->getName()); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>

                                    <!-- Attendees List -->
                                    <p><strong>Attendees:</strong></p>
                                    <ul>
                                        <?php foreach ($event->getAttendees() as $attendee): ?>
                                            <li><?php echo htmlspecialchars($attendee->getName()); ?></li>
                                        <?php endforeach; ?>
                                    </ul>

                                    <!-- Tickets List -->
                                    <p><strong>Tickets Issued:</strong></p>
                                    <ul>
                                        <?php foreach ($event->getTickets() as $ticket): ?>
                                            <li>Ticket ID: <?php echo htmlspecialchars($ticket->getId()); ?>, Attendee ID: <?php echo htmlspecialchars($ticket->getAttendeeID()); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // JavaScript to toggle additional fields based on event type
        function toggleEventTypeFields() {
            const eventType = document.getElementById('event_type').value;
            const commonFields = document.getElementById('common_event_fields');
            const fundraiserFields = document.getElementById('fundraiser_fields');
            const workshopFields = document.getElementById('workshop_fields');

            if (eventType) {
                commonFields.style.display = 'block';
            } else {
                commonFields.style.display = 'none';
            }

            if (eventType === 'fundraiser') {
                fundraiserFields.style.display = 'block';
                workshopFields.style.display = 'none';
            } else {
                fundraiserFields.style.display = 'none';
            }

            if (eventType === 'workshop') {
                workshopFields.style.display = 'block';
                fundraiserFields.style.display = 'none';
            } else {
                workshopFields.style.display = 'none';
            }
        }
    </script>
</body>
</html>

