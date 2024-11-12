package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;
import lombok.Getter;
import lombok.Setter;
import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.PaymentMethod;


@Setter
@Getter
public abstract class OnlinePayment implements PaymentMethod{
    private String email;
    private String password;

    public OnlinePayment(String email, String password)
    {
        this.email = email;
        this.password = password;
    }
}


