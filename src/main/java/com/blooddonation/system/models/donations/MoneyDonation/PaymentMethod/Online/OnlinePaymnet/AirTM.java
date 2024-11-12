package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;

public class AirTM extends OnlinePayment {

    public AirTM(String email, String password) {
        super(email, password);
    }

    @Override
    public boolean processPayment(float amount) {
        System.out.println("Payment of " + amount + " was made using AirTM");
        return true;
    }
}
