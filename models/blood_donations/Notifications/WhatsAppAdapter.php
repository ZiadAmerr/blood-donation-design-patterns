<?php

require_once 'NotificationSender.php';
require_once 'WhatsAppService.php';

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
        $ret = $this->whatsappService->notify(
            $this->donor->getPhoneNumber(),
            $this->message
        );

        return json_encode([
            'success' => $ret === "OK",
            'status' => match ($ret) {
                "OK" => 'success',
                "Error!" => 'connectivity_error',
                false => 'server_error',
                default => 'UNKNOWN_RETURN_FROM_API',
            },
            'message' => match ($ret) {
                "OK" => 'Notification sent successfully',
                "Error!" => 'Curl Error',
                false => 'Quota Exceeded or Unknown Error from WhatsApp API',
                default => 'UNKNOWN_RETURN_FROM_API',
            }
        ]);
    }
}


?>