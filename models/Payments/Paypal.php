<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

class Paypal extends OnlinePayment {

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
        
    }
    public function processPayment()
    {
        echo "Payment done using paypal";
    }
}

?>

