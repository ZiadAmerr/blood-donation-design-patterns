<?php

require_once 'NotificationSender.php';
require_once 'PushNotificationsService.php';

class PushNotificationAdapter implements NotificationSender {
    private $pushService;

    public function __construct(PushNotificationsService $pushService) {
        $this->pushService = $pushService;
    }

    public function sendNotification() {
        $this->pushService->sendPushNotification();
    }
}

?>