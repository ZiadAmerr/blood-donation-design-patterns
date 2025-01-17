<?php
// File: MoneyDonationController.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Cash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/EWallet.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankCard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';

class MoneyDonationController
{
    public function getDonations(): array
    {
        // Fetch past money donations from the database
        // This is a placeholder, replace with actual database fetching logic
        return MoneyDonation::fetchAllMoneyDonations("SELECT * FROM MoneyDonation");
    }

    public function processDonation(array $data): array
    {
        $amount = 0;
        $success = false;

        if ($data['payment_method'] === 'Cash') {
            $amount = floatval($data['cash_amount']);
            $cash = new Cash();
            $success = $cash->donate($amount);
        } elseif ($data['payment_method'] === 'EWallet') {
            $email = $data['email'];
            $password = $data['password'];
            $amount = floatval($data['ewallet_amount']);
            $ewallet = new EWallet($email, $password);
            $success = $ewallet->processPayment($amount);
        } elseif ($data['payment_method'] === 'BankCard') {
            $cardNumber = $data['card_number'];
            $cvv = $data['cvv'];
            $expiryDate = $data['expiry_date'];
            $amount = floatval($data['card_amount']);
            $bankCard = new BankCard($cardNumber, $cvv, $expiryDate);
            $success = $bankCard->processPayment($amount);
        }

        if ($success) {
            $donor = Donor::create(
                $data['donor_name'],
                $data['dob'],
                $data['national_id'],
                $data['address'],
                $data['phone']
            );

            // Add donation to the database
            MoneyDonation::create($amount, $data['dob'], $data['national_id']);
            return ['success' => true, 'message' => "Donation of $amount was successful!"];
        } else {
            return ['success' => false, 'message' => "Donation failed."];
        }
    }
}
?>