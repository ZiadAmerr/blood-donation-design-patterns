package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

import jakarta.persistence.Entity;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import jakarta.persistence.DiscriminatorValue;

@Entity  // Marks this class as a JPA entity
public class Credit extends BankPayment {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY) // Auto-generate primary key
    private int id;

    // Default constructor required by JPA
    public Credit() {
        super("", "", "");
    }

    // Constructor with parameters to initialize the payment info
    public Credit(String cardNumber, String expiryDate, String cvv) {
        super(cardNumber, expiryDate, cvv);
    }

    @Override
    public boolean processPayment(float amount) {
        // Implement the credit card payment processing logic
        return true;
    }

    // Getters and Setters (or use Lombok @Getter, @Setter if applicable)
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }
}
