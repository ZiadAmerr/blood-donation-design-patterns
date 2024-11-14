package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.PaymentMethod;
import jakarta.persistence.MappedSuperclass;
import lombok.Getter;
import lombok.Setter;

@Getter
@Setter
@MappedSuperclass
public abstract class BankPayment implements PaymentMethod {
    private String cardNumber;
    private String expiryDate;
    private String cvv;

    // Default no-argument constructor for JPA
    public BankPayment() {
    }

    public BankPayment(String cardNumber, String expiryDate, String cvv) {
        this.cardNumber = cardNumber;
        this.expiryDate = expiryDate;
        this.cvv = cvv;
    }
}
