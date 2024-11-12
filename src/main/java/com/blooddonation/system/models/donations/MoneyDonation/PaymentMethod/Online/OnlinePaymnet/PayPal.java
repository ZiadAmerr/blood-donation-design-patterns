package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;

public class PayPal extends OnlinePayment {

    public PayPal(String email, String password) {
        super(email, password);
    }

    @Override
    public boolean processPayment(float amount) {
        System.out.println("Payment of " + amount + " has been processed using PayPal");
        return true;
    }

}
