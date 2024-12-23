<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class CreditCard extends BankPayment {

    public function __construct($cardNumber, $cvv, $expiryDate)
    {
        $this->cardNumber = $cardNumber;
        $this->cvv = $cvv;
        $this->expiryDate = $expiryDate;
        
    }
    public function processPayment()
    {
        echo "Payment done using Credit Card";
    }
}

?>

