<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class Cash implements IMoneyDonationMethod {
    public function donate($donate): bool {
        //TODO: HANDLE CASH PAYMENTS HERE..
        return true;
    }
}