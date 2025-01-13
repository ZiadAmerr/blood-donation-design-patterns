<?php
// File: Cash.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
// IMPORTANT: use a slash (/) rather than a dot+slash (./) after __DIR__
require_once __DIR__ . './IMoneyDonationMethod.php';

class Cash implements IMoneyDonationMethod
{
    // Must match: donate(float $amount): bool
    public function donate(float $amount): bool
    {
        return true;
    }
}
