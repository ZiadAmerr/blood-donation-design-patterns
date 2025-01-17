<?php
// File: BankGatewayProxy.php

require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once __DIR__ . '/IBankGateway.php';
require_once __DIR__ . '/RealBankGateway.php';

class BankGatewayProxy implements IBankGateway
{
    private RealBankGateway $realBankGateway;

    public function __construct()
    {
        $this->realBankGateway = new RealBankGateway();
    }

    public function validatePayment(float $amount, string $cardNumber, string $expiryDate, string $cvv): bool
    {
        // Proxy logic: basic security checks or logging before forwarding
        if ($amount > 10000) {
            error_log("[SECURITY] High payment amount detected: $amount");
        }

        if (empty($cardNumber) || empty($expiryDate) || empty($cvv)) {
            return false; // Reject if any field is empty
        }

        // Forward the request to the real bank gateway
        return $this->realBankGateway->validatePayment($amount, $cardNumber, $expiryDate, $cvv);
    }
}
?>
