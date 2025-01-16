<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class BankCard implements IPaymentMethod {
    private int $cardNumber;
    private string $cvv;
    private float $expiryDate;
    private IBankGateway $bankGateway;

    public function __construct($cardNumber, $cvv, $expiryDate) {
        $this->cardNumber = $cardNumber;
        $this->cvv = $cvv;
        $this->expiryDate = $expiryDate;
        $this->bankGateway = new BankGatewayProxy();
    }

    public function processPayment($amount): bool {
        return $this->bankGateway->validatePayment($amount, $this->cardNumber, $this->expiryDate, $this->cvv);
    }
}