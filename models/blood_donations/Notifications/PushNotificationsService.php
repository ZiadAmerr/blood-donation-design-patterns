<?php

require_once 'NotificationService.php';

class PushNotificationsService implements NotificationService {
    public function sendPushNotification() {
        echo "Push Notification Sent!\n";
    }

    public function notifyUser() {
        $this->sendPushNotification();
    }
}
?>