<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

abstract class BankPayment implements PaymentMethod {
    protected string $cardNumber;
    protected int $cvv;
    protected string $expiryDate;
}