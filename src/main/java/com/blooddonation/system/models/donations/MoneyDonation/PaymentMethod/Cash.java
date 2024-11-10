package com.blooddonation.system.models.donations.MoneyDonation.PaymentMethod;

import com.blooddonation.system.models.donations.DonationMethod;

public class Cash implements DonationMethod {
    @Override
    public boolean donate(float amount) {
        return true;
    }

}
