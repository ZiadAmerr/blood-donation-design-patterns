



<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/controllers/EventController.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/Volunteer/Volunteer.php';

$eventController = new EventController();
$campaigns = $eventController->getDonationCampaigns();

foreach ($campaigns as $campaign): ?> 
                    <option value="<?php echo $campaign->getId(); ?>">
                        <?php echo ($campaign->getTitle()); ?>
                    </option>
                <?php endforeach; ?>