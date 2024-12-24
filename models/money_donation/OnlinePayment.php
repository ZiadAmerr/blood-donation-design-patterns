<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

abstract class OnlinePayment implements PaymentMethod {
    protected string $email;
    protected string $password;
}