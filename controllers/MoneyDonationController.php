<?php
// File: MoneyDonationController.php
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Payment/Cash.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/EWallet.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/Online/BankCard.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/people/Donor.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/models/MoneyDonation/MoneyDonation.php';

class MoneyDonationController {
    private $moneyDonation;
    private $moneyStock;

    public function __construct() {
        $this->moneyDonation = new MoneyDonation();
        $this->moneyStock = MoneyStock::getInstance();
    }

    public function fetchAll(): array {
        return $this->moneyDonation->fetchAll();
    }

    public function processDonation(array $data): array {
        try {
            $amount = $this->getAmount($data);
            
            if ($amount <= 0) {
                return ['success' => false, 'message' => 'Invalid amount'];
            }

            $paymentMethod = $this->createPaymentMethod($data);
            if (!$paymentMethod->processPayment($amount)) {
                return ['success' => false, 'message' => 'Payment processing failed'];
            }

            if (!$this->moneyDonation->create($amount, date('Y-m-d'), $data['donor_id'])) {
                return ['success' => false, 'message' => 'Failed to record donation'];
            }

            return ['success' => true, 'message' => "Successfully donated $amount"];
        } catch (Exception $e) {
            error_log($e->getMessage());
            return ['success' => false, 'message' => 'An error occurred'];
        }
    }

    private function getAmount(array $data): float {
        switch($data['payment_method']) {
            case 'Cash': return floatval($data['cash_amount']);
            case 'EWallet': return floatval($data['ewallet_amount']);
            case 'BankCard': return floatval($data['card_amount']);
            default: throw new Exception('Invalid payment method');
        }
    }

    private function createPaymentMethod(array $data): IPaymentMethod {
        switch($data['payment_method']) {
            case 'Cash': 
                return new Cash();
            case 'EWallet': 
                return new EWallet($data['email'], $data['password']);
            case 'BankCard': 
                return new BankCard($data['card_number'], $data['cvv'], $data['expiry_date']);
            default: 
                throw new Exception('Invalid payment method');
        }
    }
}
?>