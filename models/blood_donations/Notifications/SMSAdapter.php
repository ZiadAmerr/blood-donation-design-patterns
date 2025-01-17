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
        $payload = json_encode([
            'recipient' => $this->donor->getPhoneNumber(),
            'contents' => $this->message
        ]);

        $ret = $this->smsService->notify($payload);

        return json_encode([
            'success' => $ret === true,
            'status' => match ($ret) {
                true => 'success',
                false => 'payload_error',
                0 => 'connectivity_error',
                default => 'UNKNOWN_RETURN_FROM_API',
            },
            'message' => match ($ret) {
                true => 'Notification sent successfully',
                false => 'Payload Error',
                0 => 'Curl Error',
                default => 'UNKNOWN_RETURN_FROM_API',
            }
        ]);
    }
}

?>
