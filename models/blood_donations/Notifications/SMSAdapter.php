<?php

require_once 'NotificationSender.php';
require_once 'SmsService.php';

class SMSAdapter implements NotificationSender {
    private SmsService $smsService;
    private Donor $donor;
    private string $message;

    public function __construct(SmsService $smsService, Donor $donor, string $message) {
        $this->smsService = $smsService;
        $this->donor = $donor;
        $this->message = $message;
    }

    public function sendNotification() {
        $ret = $this->smsService->notify(
            $this->donor->getPhoneNumber(),
            $this->message
        );

        return json_encode([
            'success' => $ret === true,
            'status' => match ($ret) {
                true => 'success',
                0 => 'connectivity_error',
                default => 'UNKNOWN_RETURN_FROM_API',
            },
            'message' => match ($ret) {
                true => 'Notification sent successfully',
                0 => 'Curl Error',
                default => 'UNKNOWN_RETURN_FROM_API',
            }
        ]);
    }
}

?>