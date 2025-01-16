<?php

require_once 'NotificationService.php';

class WhatsAppService implements NotificationService {
    public function sendWhatsapp() {
        echo "WhatsApp Message Sent!\n";
    }

    public function notifyUser() {
        $this->sendWhatsapp();
    }
}

?>