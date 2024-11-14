package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;

import jakarta.persistence.*;

@Entity
public class AirTM extends OnlinePayment {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)  // Auto-generated primary key
    private int id;

    public AirTM(String email, String password) {
        super(email, password);
    }

    public AirTM() {
        super();
    }

    @Override
    public boolean processPayment(float amount) {
        // Implement AirTM specific payment logic
        System.out.println("Payment of " + amount + " was made using AirTM.");
        return true;
    }
}
