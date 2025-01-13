<?php
// File: IMoneyDonationMethod.php

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
