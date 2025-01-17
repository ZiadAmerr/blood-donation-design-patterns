<?php
// File: MoneyDonationController.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Cash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Online/EWallet.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Online/BankCard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';

class MoneyDonationController
{
    public function getDonations(): array
    {
        return MoneyDonation::fetchAllMoneyDonations();
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
            // $donor = Donor::create(
            //     $data['donor_name'],
            //     $data['date_of_birth'],
            //     $data['national_id'],
            //     $data['address_id'],
            //     $data['phone_number']
            // );

            // Add donation to the database
            MoneyDonation::create($amount, date('Y-m-d'), $data['national_id']);
            return ['success' => true, 'message' => "Donation of $amount was successful!"];
        } else {
            return ['success' => false, 'message' => "Donation failed."];
        }
    }
}
?>