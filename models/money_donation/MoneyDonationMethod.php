<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

interface MoneyDonationMethod {
    public function donate($amount): bool;
}
