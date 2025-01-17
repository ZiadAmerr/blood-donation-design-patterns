<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Online/BankGateway/IBankGateway.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Online/BankGateway/BankGatewayProxy.php';


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

        else if ($this->bankGateway->validatePayment($amount, $this->cardNumber, $this->expiryDate, $this->cvv)){
            MoneyStock::getInstance()->addCash($amount);
            return true;
        }
        else{
            return false;
        }
    }
}
?>
