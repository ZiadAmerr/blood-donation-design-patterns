<?php
// File: Cash.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

// IMPORTANT: use a slash (/) rather than a dot+slash (./) after __DIR__
require_once $_SERVER['DOCUMENT_ROOT'] . './models/MoneyDonation/IMoneyDonationMethod.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyStock.php';

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
