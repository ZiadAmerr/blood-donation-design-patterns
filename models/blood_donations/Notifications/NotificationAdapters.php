<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/models/blood_donations/Notifications/NotficationsSender.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/whatsapp_service.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/sms_service.php";

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

class WhatsAppAdapter implements NotificationSender {
    private WhatsAppService $whatsappService;
    private Donor $donor;
    private string $message;

    public function __construct(WhatsAppService $whatsappService, Donor $donor, string $message) {
        $this->whatsappService = $whatsappService;
        $this->donor = $donor;
        $this->message = $message;
    }

    public function sendNotification(): string {
        $payload = json_encode([
            'target' => $this->donor->getPhoneNumber(),
            'contents' => $this->message,
            'source' => 'BloodDonationSystem',
            'token' => $this->whatsappService->api_token
        ]);

        $ret = $this->whatsappService->notify(
            $this->donor->getPhoneNumber(),
            $this->message
        );

        return json_encode([
            'success' => $ret === "OK",
            'status' => match ($ret) {
                "OK" => 'success',
                0 => 'payload_error',
                "Error!" => 'connectivity_error',
                false => 'server_error',
                default => 'UNKNOWN_RETURN_FROM_API',
            },
            'message' => match ($ret) {
                "OK" => 'Notification sent successfully',
                0 => 'Payload Error',
                "Error!" => 'Curl Error',
                false => 'Quota Exceeded or Unknown Error from WhatsApp API',
                default => 'UNKNOWN_RETURN_FROM_API',
            }
        ]);
    }
}

?>
