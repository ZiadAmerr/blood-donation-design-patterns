<?php
// File: Cash.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once __DIR__ . '/IMoneyDonationMethod.php';

class Cash implements IMoneyDonationMethod
{
    public function donate(float $amount, string $currency): bool
    {
        // In real life, you might simply log that the user paid cash,
        // record it in the database, or generate a receipt.
        echo "Simulating cash donation of amount: {$amount}<br>";
        return true;
    }
}
