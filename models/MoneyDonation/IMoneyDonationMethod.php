<?php
// File: IMoneyDonationMethod.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

interface IMoneyDonationMethod
{
    /**
     * donate($amount): bool
     *
     * Processes the donation of a certain amount of money.
     * Returns true on success, false on failure.
     */
    public function donate(float $amount): bool;
}
