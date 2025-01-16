<?php

require_once 'NotificationService.php';

class EmailService implements NotificationService {
    public function sendEmail() {
        echo "Email Sent!\n";
    }

    public function notifyUser() {
        $this->sendEmail();
    }
}

?>