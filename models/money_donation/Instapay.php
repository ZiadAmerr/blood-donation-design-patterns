<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class Instapay extends OnlinePayment {
    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function processPayment($amount): bool{
        // Process payment using Instapay API
        //TODO: IMPLEMENT INSTAPAY API HERE..
        return true;
    }
}