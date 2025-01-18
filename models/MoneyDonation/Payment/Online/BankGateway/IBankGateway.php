<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

interface IBankGateway {
    public function validatePayment(float $amount, string $cardNumber, string $expiryDate, string $cvv): bool;
}