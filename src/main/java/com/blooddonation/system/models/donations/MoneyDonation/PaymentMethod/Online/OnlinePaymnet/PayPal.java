package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;

import jakarta.persistence.Entity;
import jakarta.persistence.Id;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;

@Entity
public class PayPal extends OnlinePayment {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)  // Auto-generated primary key
    private int id;

    public PayPal(String email, String password) {
        super(email, password);
    }

    public PayPal() {

    }

    @Override
    public boolean processPayment(float amount) {
        System.out.println("Payment of " + amount + " has been processed using PayPal.");
        return true;
    }
}
