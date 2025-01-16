<?php

require_once 'NotificationSender.php';
require_once 'EmailService.php';

class EmailAdapter implements NotificationSender {
    private $emailService;

    public function __construct(EmailService $emailService) {
        $this->emailService = $emailService;
    }

    public function sendNotification() {
        $this->emailService->sendEmail();
    }
}

?>
