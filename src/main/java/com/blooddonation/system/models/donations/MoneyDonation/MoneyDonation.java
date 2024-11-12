package com.blooddonation.system.models.donations.MoneyDonation;

import com.blooddonation.system.models.donations.Donation;
import com.blooddonation.system.models.donations.DonationMethod;
import com.blooddonation.system.models.people.Donor;

public class MoneyDonation extends Donation {
    private DonationMethod donationMethod;
    private MoneyDonationDetails moneyDonationDetails;

    public MoneyDonation(DonationMethod donationMethod, MoneyDonationDetails moneyDonationDetails, Donor donor) {
        super(donor);
        this.donationMethod = donationMethod;
        this.moneyDonationDetails = moneyDonationDetails;
    }

    private String get_receipt(){
        float amount = 50.5F;
        donationMethod.donate(amount);
        return "receipt to be implemented using the adapter design pattern.";
    }
}
