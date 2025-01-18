<?php
// File: RealBankGateway.php

class RealBankGateway implements IBankGateway
{
    public function validatePayment(float $amount, string $cardNumber, string $expiryDate, string $cvv): bool
    {
        // Check if the amount is valid
        if ($amount <= 0) {
            return false;
        }

        // Validate card number (Luhn Algorithm for simplicity)
        if (!$this->isValidCardNumber($cardNumber)) {
            return false;
        }

        // Validate expiry date (format MM/YY and future date)
        if (!$this->isValidExpiryDate($expiryDate)) {
            return false;
        }

        // Validate CVV (3 or 4 digits)
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            return false;
        }

        // All validations passed
        return true;
    }

    private function isValidCardNumber(string $cardNumber): bool
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        $sum = 0;
        $alt = false;
        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $n = (int)$cardNumber[$i];
            if ($alt) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }
            $sum += $n;
            $alt = !$alt;
        }
        return ($sum % 10 === 0);
    }

    private function isValidExpiryDate(string $expiryDate): bool
    {
        if (!preg_match('/^(0[1-9]|1[0-2])\/(\d{2})$/', $expiryDate)) {
            return false;
        }
        list($month, $year) = explode('/', $expiryDate);
        $currentYear = (int)date('y');
        $currentMonth = (int)date('m');
        $expiryYear = (int)$year;
        $expiryMonth = (int)$month;

        return ($expiryYear > $currentYear) || ($expiryYear === $currentYear && $expiryMonth >= $currentMonth);
    }
}
?>
