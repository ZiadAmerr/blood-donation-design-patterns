<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class PayPal extends OnlinePayment {
    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function processPayment($amount): bool {
        // Process payment using Paypal API
        // TODO: IMPLEMENT PAYPAL API HERE..
        return true;
    }
}
