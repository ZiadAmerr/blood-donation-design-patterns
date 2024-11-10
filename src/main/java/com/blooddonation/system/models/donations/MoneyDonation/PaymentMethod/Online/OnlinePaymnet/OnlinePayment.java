package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;
import lombok.Getter;
import lombok.Setter;

@Setter
@Getter
public abstract class OnlinePayment {
    private String email;
    private String password;

    public abstract boolean processPayment(float amount);
}
