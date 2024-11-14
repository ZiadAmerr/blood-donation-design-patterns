package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

import jakarta.persistence.*;

@Entity  // Marks this class as a JPA entity
public class Debit extends BankPayment {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private int id;

    public Debit(String cardNumber, String expiryDate, String cvv) {
        super(cardNumber, expiryDate, cvv);
    }

    public Debit() {
        super();
    }

    @Override
    public boolean processPayment(float amount) {
        // Logic for processing the payment
        System.out.println("Processing debit payment of " + amount + " USD.");
        return true;
    }

    public void setId(int id) {
        this.id = id;
    }

    public int getId() {
        return id;
    }
}
