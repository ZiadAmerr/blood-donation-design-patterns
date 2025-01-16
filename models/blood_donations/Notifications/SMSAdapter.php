<?php

require_once 'NotificationSender.php';
require_once 'SmsService.php';

class SMSAdapter implements NotificationSender {
    private $smsService;

    public function __construct(SmsService $smsService) {
        $this->smsService = $smsService;
    }

    public function sendNotification() {
        $this->smsService->sendSms();
    }
}

?>