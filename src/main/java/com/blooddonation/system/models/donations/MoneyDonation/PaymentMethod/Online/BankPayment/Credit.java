package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

public class Credit extends BankPayment {

    public Credit(String cardNumber, String expiryDate, String cvv) {
        super(cardNumber, expiryDate, cvv);
    }

    @Override
    public boolean processPayment(float amount) {
        return true;
    }
}
