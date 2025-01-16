<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

interface IMoneyDonationMethod {
    public function donate($amount): bool;
}
