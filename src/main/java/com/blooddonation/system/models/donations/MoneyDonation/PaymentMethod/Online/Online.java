package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.Online;

import com.blooddonation.system.models.donations.DonationMethod;

public class Online implements DonationMethod {
    private com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod.paymentMethod paymentMethod;
    @Override
    public boolean donate(float amount) {
        return paymentMethod.processPayment(amount);
    }
}
