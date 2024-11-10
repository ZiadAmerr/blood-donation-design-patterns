package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.BankPayment;

import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.paymentMethod;
import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public abstract class BankPayment implements paymentMethod {
    private String cardNumber;
    private String expiryDate;
    private String cvv;
}
