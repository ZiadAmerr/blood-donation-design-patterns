<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/services/database_service.php";

interface PaymentMethod {
    public function processPayment();
    
}

?>

