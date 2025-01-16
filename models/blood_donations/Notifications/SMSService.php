<?php

require_once 'NotificationService.php';

class SmsService implements NotificationService {
    public function sendSms() {
        echo "SMS Sent!\n";
    }

    public function notifyUser() {
        $this->sendSms();
    }
}

?>