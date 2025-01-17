<?php
// File: Cash.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

// IMPORTANT: use a slash (/) rather than a dot+slash (./) after __DIR__
require_once __DIR__ . './IMoneyDonationMethod.php';

class Cash implements IMoneyDonationMethod
{
    public function donate(float $amount): bool
    {
        if ($amount <= 0) {
            return false;  // Invalid donation amount
        }

        MoneyStock::getInstance()->addCash($amount);
        return true;  // Donation successful
    }
}
