<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class Online implements IMoneyDonationMethod {
    private IPaymentMethod $paymentMethod;

    public function __construct($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function donate($amount): bool {
        if ($this->paymentMethod->processPayment($amount)){
            MoneyStock::getInstance()->addCash($amount);
        }
        return true;
    }
}