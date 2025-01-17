<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/IPaymentMethod.php';

class EWallet implements IPaymentMethod {
    protected string $email;
    protected string $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function processPayment(float $amount): bool {
        // Process payment using EWallet API
        // TODO: IMPLEMENT EWallet API HERE..
        return true;
    }
}
