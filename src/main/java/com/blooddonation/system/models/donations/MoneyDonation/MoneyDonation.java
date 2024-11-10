package com.blooddonation.system.models.donations.MoneyDonation;

import com.blooddonation.system.models.donations.DonationMethod;

public class MoneyDonation {
    private DonationMethod donationMethod;
    private MoneyDonationDetails moneyDonationDetails;

    private String get_receipt(){
        float amount = 50.5F;
        donationMethod.donate(amount);
        return "receipt to be implemented using the adapter design pattern.";
    }
}
