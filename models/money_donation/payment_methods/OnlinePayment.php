<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

abstract class OnlinePayment implements IPaymentMethod {
    protected string $email;
    protected string $password;
}