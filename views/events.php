<?php
// File: donation_campaigns_view.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/EventController.php';

$controller = new EventController();
$campaigns = $controller->getDonationCampaigns(); // Get all campaigns
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Campaigns and Events</title>
    <style>
        .campaign-list, .event-list {
            list-style-type: none;
            padding: 0;
        }
        .campaign-item, .event-item {
            margin: 10px 0;
        }
        .event-item {
            padding-left: 20px;
        }
        .event-link, .campaign-link {
            text-decoration: none;
            color: #007bff;
        }
        .event-link:hover, .campaign-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Donation Campaigns and Events</h1>

    <?php if (!empty($campaigns)): ?>
        <ul class="campaign-list">
            <?php foreach ($campaigns as $campaign): ?>
                <li class="campaign-item">
                    <a href="campaign_details.php?id=<?php echo $campaign->getId(); ?>" class="campaign-link">
                        <?php echo htmlspecialchars($campaign->getTitle()); ?>
                    </a>
                    <!-- Display events inside the campaign -->
                    <?php
                    $events = $campaign->getEvents(); // Assuming the Campaign model has a getEvents method
                    if (!empty($events)): ?>
                        <ul class="event-list">
                            <?php foreach ($events as $event): ?>
                                <li class="event-item">
                                    <a href="event_details.php?id=<?php echo $event->getId(); ?>" class="event-link">
                                        <?php echo htmlspecialchars($event->getTitle()); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No donation campaigns available.</p>
    <?php endif; ?>
</body>
</html>
