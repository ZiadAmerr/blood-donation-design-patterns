<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankGateway/IBankGateway.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankGateway/BankGatewayProxy.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/IPaymentMethod.php';

class BankCard implements IPaymentMethod
{
    private string $cardNumber;
    private string $cvv;
    private string $expiryDate;
    private IBankGateway $bankGateway;

    public function __construct(string $cardNumber, string $cvv, string $expiryDate)
    {
        $this->cardNumber = $cardNumber;
        $this->cvv = $cvv;
        $this->expiryDate = $expiryDate;
        $this->bankGateway = new BankGatewayProxy();
    }

    public function processPayment(float $amount): bool
    {
        if ($amount <= 0) {
            return false;  // Invalid payment amount
        }

        if ($this->bankGateway->validatePayment($amount, $this->cardNumber, $this->expiryDate, $this->cvv)){
            MoneyStock::getInstance()->addCash($amount);
            return true;
        }
        else{
            false;
        }
    }
}
?>
