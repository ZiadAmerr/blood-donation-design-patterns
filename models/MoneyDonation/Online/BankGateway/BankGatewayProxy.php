<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class BankGatewayProxy implements IBankGateway {
    private RealBankGateway $realBankGateway;

    public function __construct() {
        $this->realBankGateway = new RealBankGateway();
    }

    public function validatePayment(float $amount, string $cardNumber, string $expiryDate, string $cvv): bool {
        // Add proxy logic if needed, for now, just forward the request
        return $this->realBankGateway->validatePayment($amount, $cardNumber, $expiryDate, $cvv);
    }
}

?>