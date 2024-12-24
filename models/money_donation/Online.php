<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class Online implements IMoneyDonationMethod {
    private PaymentMethod $paymentMethod;

    public function __construct($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function donate($amount): bool {
        $this->paymentMethod->processPayment($amount);
        return true;
    }
}