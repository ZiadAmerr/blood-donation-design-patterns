package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

public class Debit extends BankPayment {

    public Debit(String cardNumber, String expiryDate, String cvv) {
        super(cardNumber, expiryDate, cvv);
    }

    @Override
    public boolean processPayment(float amount) {
        // method to be implemented
        return true;
    }
}