<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/services/database_service.php';

class RealBankGateway implements IBankGateway {
    public function validatePayment(float $amount, string $cardNumber, string $expiryDate, string $cvv): bool {
        // Simulate real bank gateway validation logic
        if ($amount > 0 && strlen($cardNumber) == 16 && strlen($cvv) == 3) {
            return true;
        }
        return false;
    }
}

?>