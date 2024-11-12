package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online;
import com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.PaymentMethod;
import com.blooddonation.system.models.donations.DonationMethod;

public class Online implements DonationMethod {
    private PaymentMethod paymentMethod;

    public Online(PaymentMethod paymentMethod)
    {
        this.paymentMethod = paymentMethod;
    }

    @Override
    public boolean donate(float amount) {
        return paymentMethod.processPayment(amount);
    }
}
