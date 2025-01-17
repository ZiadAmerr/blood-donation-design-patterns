<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

interface IPaymentMethod {
    public function processPayment(float $amount): bool;
}