package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

public class Debit extends BankPayment {

    public Debit(String cardNumber, String expiryDate, String cvv, String cardType) {
        this.setCardNumber(cardNumber);
        this.setExpiryDate(expiryDate);
        this.setCvv(cvv);
    }

    @Override
    public boolean processPayment(float amount) {
        // method to be implemented
        return true;
    }
}