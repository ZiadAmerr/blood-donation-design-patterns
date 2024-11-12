package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.PaymentMethod;
import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public abstract class BankPayment implements PaymentMethod {
    private String cardNumber;
    private String expiryDate;
    private String cvv;

    public BankPayment(String cardNumber, String expiryDate, String cvv) {
        this.cardNumber = cardNumber;
        this.expiryDate = expiryDate;
        this.cvv = cvv;
    }
}
