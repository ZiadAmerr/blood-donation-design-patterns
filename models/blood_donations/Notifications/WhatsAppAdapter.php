<?php

require_once 'NotificationSender.php';
require_once 'WhatsAppService.php';

class WhatsAppAdapter implements NotificationSender {
    private $whatsappService;

    public function __construct(WhatsAppService $whatsappService) {
        $this->whatsappService = $whatsappService;
    }

    public function sendNotification() {
        $this->whatsappService->sendWhatsapp();
    }
}

?>