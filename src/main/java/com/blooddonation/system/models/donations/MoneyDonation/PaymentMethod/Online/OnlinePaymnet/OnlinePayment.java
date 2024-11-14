package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online.OnlinePaymnet;
import jakarta.persistence.GeneratedValue;
import jakarta.persistence.GenerationType;
import jakarta.persistence.Id;
import lombok.Getter;
import lombok.Setter;
import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.PaymentMethod;


@Setter
@Getter
public abstract class OnlinePayment implements PaymentMethod{
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private int id;

    private String email;
    private String password;

    public OnlinePayment(String email, String password)
    {
        this.email = email;
        this.password = password;
    }

    public OnlinePayment() {

    }
}


