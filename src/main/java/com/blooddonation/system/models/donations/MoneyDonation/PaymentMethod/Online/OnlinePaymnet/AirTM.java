package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;

public class AirTM extends OnlinePayment {
    @Override
    public boolean processPayment(float amount) {
        System.out.println("Payment of " + amount + " was made using AirTM");
        return true;
    }
}
